<?php

namespace App\Http\Controllers;

use App\Helper\ResponseFormatter;
use App\Models\cartItemsModel;
use App\Models\cartsModel;
use App\Models\categoryModel;
use App\Models\discount;
use App\Models\location;
use App\Models\product;
use App\Models\product_group;
use App\Models\ProductViewHistory;
use App\Models\recipeModel;
use App\Models\SearchHistory;
use App\Models\User;
use App\Models\user_save_recipe;
use Exception;
use Google\Service\Books\Category;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Http;
use Google_Client;
use Google_Service_YouTube;

class ApiController extends Controller
{
    public function getCategory()
    {
        $categoryCacheKey = 'categories.all';

        $category = Redis::get($categoryCacheKey);
        if (!$category) {
            $category = categoryModel::all();
            Redis::setex($categoryCacheKey, 300, $category);
        }
        return ResponseFormatter::success(json_decode($category), 'Berhasil mendapatkan category ');
    }

    public function getProductByCity(Request $request)
    {
        $category = $request->query('category_id');
        $cityName = $request->query('city_name');

        // // Cek apakah hasil pencarian sudah ada di cache Redis
        $cacheKey = "products-$category-$cityName";
        $cachedResult = Redis::get($cacheKey);
        if ($cachedResult !== null) {
            return ResponseFormatter::success(json_decode($cachedResult), 'Berhasil mendapatkan Products ');
        }

        $city = Location::where('city', $cityName)->first();

        if (!$city) {
            return response()->json([
                'message' => 'City not found',
                'status' => 404,
            ], 404);
        }

        $query = product::whereHas('locations', function ($query) use ($city) {
            $query->where('location_id', $city->id);
        });

        $filters = [
            'category_id' => $category,
            'min_price' => $request->query('min_price'),
            'max_price' => $request->query('max_price')
        ];

        $query = $this->applyFilters($query, $filters);

        $products = $query->get();

        $response = $products->map(function ($product) {
            $data = $product->toArray();

            if ($product->discount_id) {
                $discount = discount::find($product->discount_id);
                $data['discount_percentage'] = $discount->discount_percetage;
                $data['now_price'] = $product->price - ($product->price * $discount->discount_percetage / 100);
            } else {
                $data['discount_percentage'] = 0;
                $data['now_price'] = $product->price;
            }

            return $data;
        });

        // Simpan hasil pencarian di cache Redis selama 1 jam

        return ResponseFormatter::success($response, 'Berhasil mendapatkan product ');
    }
    public function getProductGroupByCity(Request $request)
    {
        $cityName = $request->query('city_name');
        $page = $request->query('page') ?? 1;
        $limit = $request->query('limit') ?? 10;


        $redis = Redis::connection();
        $key = "products_city_{$cityName}_page_{$page}_limit_{$limit}";

        if ($redis->exists($key)) {
            // jika data sudah tersimpan pada Redis, maka gunakan data dari Redis
            $groupedProducts = unserialize($redis->get($key));
        } else {
            // jika data belum tersimpan pada Redis, maka query database dan simpan hasil query ke Redis
            $city = location::where('city', $cityName)->first();

            if (!$city) {
                return response()->json([
                    'message' => 'City not found',
                    'status' => "404",
                ], 404);
            }

            $groupedProducts = product_group::with(['products' => function ($query) use ($city) {
                $query->whereHas('locations', function ($query) use ($city) {
                    $query->where('location_id', $city->id);
                });
            }])
                ->offset(($page - 1) * $limit)
                ->limit($limit)
                ->get();

            foreach ($groupedProducts as $productGroup) {
                foreach ($productGroup->products as $product) {
                    if ($product->discount_id) {
                        $discount = discount::find($product->discount_id);
                        $product['discount_percentage'] = $discount->discount_percetage;
                        $product['now_price'] = $product->price - ($product->price * $discount->discount_percetage / 100);
                    } else {
                        $product['discount_percentage'] = 0;
                        $product['now_price'] = $product->price;
                    }
                }
            }

            $redis->set($key, serialize($groupedProducts));
            $redis->expire($key, 3600); // set expiry time 1 jam
        }

        return ResponseFormatter::success($groupedProducts, 'Berhasil mendapatkan category ');
    }

    public function getProductGroupByCityDetail(Request $request)
    {
        // convert slug to normal string
        $slug = str_replace('-', ' ', $request->slug);

        $cityName = $request->query('city_name');

        $redis = Redis::connection();
        $key = "products_city_{$cityName}_{$slug}";

        // redis

        if ($redis->exists($key)) {
            $productGroup = unserialize($redis->get($key));
        }


        // find product group by slug
        $productGroup = product_group::with('products')->where('title', $slug)->first();
        foreach ($productGroup->products as $product) {
            if ($product->discount_id) {
                $discount = discount::find($product->discount_id);
                $product['discount_percentage'] = $discount->discount_percetage;
                $product['now_price'] = $product->price - ($product->price * $discount->discount_percetage / 100);
            } else {
                $product['discount_percentage'] = 0;
                $product['now_price'] = $product->price;
            }
        }
        if (!$productGroup) {
            return response()->json([
                'message' => 'Product group not found',
                'status' => "404",
            ], 404);
        }
        return ResponseFormatter::success($productGroup, 'Berhasil mendapatkan product ');
    }



    public function getDetailProduct(Request $request, $slug)
    {
        // create function to get detail product with REDIS


        $redis = Redis::connection();
        $key = "products_detail_{$request->id}";

        $name = str_replace('-', ' ', $slug);

        $product = product::with('category')->where('name', $name)->first();





        if (!$product) {
            return response()->json([
                'message' => 'Product not found',
                'status' => "404",
            ], 404);
        }

        if ($product->discount_id) {
            $discount = discount::find($product->discount_id);
            $product['discount_percentage'] = $discount->discount_percetage;
            $product['now_price'] = $product->price - ($product->price * $discount->discount_percetage / 100);
        } else {
            $product['discount_percentage'] = 0;
            $product['now_price'] = $product->price;
        }

        return ResponseFormatter::success($product, 'Berhasil mendapatkan product ');
    }


    private function applyFilters($query, $filters)
    {
        if (isset($filters['category_id'])) {
            $query = $query->where('categories_id', $filters['category_id']);
        }

        if (isset($filters['min_price'])) {
            $query = $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query = $query->where('price', '<=', $filters['max_price']);
        }

        return $query;
    }

    //similir product
    public function getSimilarProduct(Request $request)
    {
        $user = auth()->user();

        // pluck search_term dari tabel search_history
        $searchHistory = $user->searchHistory->pluck('search_term')->toArray();
        $productViewHistory = $user->productViewHistory->pluck('product_id')->toArray();
        // Gabungkan data search history dan product view history
        $terms = array_merge($searchHistory, $productViewHistory);

        // Lakukan content-based filtering dengan menggunakan data search history dan product view history
        $products = Product::all();

        // Hitung nilai cosine similarity untuk setiap produk
        foreach ($products as $product) {
            $product->similarity = $this->cosineSimilarity($terms, $product->name, $product->description);
        }

        // Urutkan produk berdasarkan nilai cosine similarity dari yang tertinggi ke terendah
        $products = $products->sortByDesc('similarity')->values();

        $response = $products->map(function ($product) {
            $data = $product->toArray();

            if ($product->discount_id) {
                $discount = discount::find($product->discount_id);
                $data['discount_percentage'] = $discount->discount_percetage;
                $data['now_price'] = $product->price - ($product->price * $discount->discount_percetage / 100);
            } else {
                $data['discount_percentage'] = 0;
                $data['now_price'] = $product->price;
            }

            return $data;
        });

        // Lakukan content-based filtering dengan menggunakan data search history dan product view history


        //return
        return ResponseFormatter::success($response, 'Berhasil mendapatkan product ');
    }


    public function getMostVisitProduct()
    {
        $user = auth()->user();
        // Mengambil data history view product oleh user
        $user_history = ProductViewHistory::where('user_id', $user->id)->pluck('product_id');

        // Mengambil data product yang tidak pernah dilihat oleh user
        $products = Product::whereNotIn('id', $user_history)->get();

        // Membuat array kosong untuk menyimpan similarity score
        $similarities = [];

        // Mengambil terms dari semua produk yang ada di database
        $terms = $this->getAllTerms();

        // Menghitung cosine similarity untuk setiap produk yang tidak pernah dilihat oleh user
        foreach ($products as $product) {
            $cosine_similarity = $this->cosineSimilarity($terms, $product->name, $product->description);
            $similarities[$product->id] = $cosine_similarity;
            $product->similarity = $cosine_similarity;
        }



        // Mengurutkan similarity score dari yang tertinggi ke terendah

        arsort($similarities);

        // Mengambil N produk dengan similarity score tertinggi sebagai rekomendasi
        $recommendations = [];
        $count = 0;
        foreach ($similarities as $product_id => $similarity) {
            if ($count >= 5) {
                break;
            }
            $recommendations[] = Product::find($product_id);
            $count++;
        }

        return $recommendations;
    }

    public function recommendRecipes()
    {

        $user = auth()->user();

        // find user
        $user = User::find($user->id);
        // Ambil semua produk yang pernah dibeli oleh user
        $cart = cartsModel::where('id', $user->carts_id)->first();
        $userProducts = cartItemsModel::where('carts_id', $cart->id)->pluck('products_id')->toArray();

        // Ambil semua resep yang mengandung setiap produk yang dibeli oleh user
        $recipes = recipeModel::all();
        $recommendedRecipes = [];
        foreach ($recipes as $recipe) {
            $recipeProducts = $recipe->products->pluck('id')->toArray();
            if (count(array_intersect($userProducts, $recipeProducts)) > 0) {
                $recommendedRecipes[] = $recipe;
            }
        }

        // Urutkan resep berdasarkan similarity
        $terms = array_unique($userProducts);
        $recipeScores = [];
        foreach ($recommendedRecipes as $recipe) {
            $recipeScores[$recipe->id] = $this->cosineSimilarity($terms, $recipe->name, $recipe->description, null);
        }
        arsort($recipeScores);

        // Ambil 5 resep dengan similarity tertinggi
        $recommendedRecipeIds = array_slice(array_keys($recipeScores), 0, 5);
        $recommendedRecipes = recipeModel::whereIn('id', $recommendedRecipeIds)->get();

        return $recommendedRecipes;
    }


    private function getAllTerms()
    {
        // Mengambil semua nama produk dan deskripsi produk dari database
        $products = Product::all();
        $terms = [];

        // Menggabungkan nama produk dan deskripsi produk menjadi satu teks dan mengubah teks menjadi array of term
        foreach ($products as $product) {
            $text = $product->name . ' ' . $product->description;
            $text = explode(' ', $text);
            $terms = array_merge($terms, $text);
        }

        // Menghapus duplicate term
        $terms = array_unique($terms);

        return $terms;
    }

    private function cosineSimilarity($terms, $name, $description)
    {
        // Gabungkan nama produk dan deskripsi produk menjadi satu teks
        $text = $name . ' ' . $description;

        // Ubah teks menjadi array of term
        $text = explode(' ', $text);

        // Hitung term frequency untuk setiap term
        $tf = array_count_values($text);

        // Hitung inverse document frequency untuk setiap term
        $idf = [];
        foreach ($terms as $term) {
            $idf[$term] = log(1 + count(Product::where('name', 'like', '%' . $term . '%')->orWhere('description', 'like', '%' . $term . '%')->get()));
        }

        // Hitung nilai cosine similarity untuk produk
        $dotProduct = 0;
        $productLength = 0;
        $termsLength = 0;
        foreach ($tf as $term => $count) {
            if (isset($idf[$term])) {
                $dotProduct += $count * $idf[$term];
                $termsLength += pow($idf[$term], 2);
            }
            $productLength += pow($count, 2);
        }
        $productLength = sqrt($productLength);
        $termsLength = sqrt($termsLength);

        // Cek apakah nilai $productLength dan $termsLength tidak sama dengan 0
        if ($productLength != 0 && $termsLength != 0) {
            $cosineSimilarity = $dotProduct / ($productLength * $termsLength);
        } else {
            $cosineSimilarity = 0;
        }

        return $cosineSimilarity;
    }

    public function searchRelevantIngredient(Request $request)
    {
        // Ambil inputan teks dari request

        $clientRequest = $request->input('text');
        $rules =
        "If the user greets me, I will respond by saying 'I am Chef AI, ready to help you find the recipe you need.' and only mention their greeting.

If the user is talking about recipes, I will only provide responses about recipes and will not provide information about products.

If the user is talking about products, I will only provide responses about products and will not provide information about recipes.

I will limit my responses according to the topic being discussed by the user and will not mix up the topics.

For recipe-related inquiries, I will provide directions in numbered points for easy understanding.

Example Recipe:
Name:
Ingredients:
Request type:
Directions:


If the user asks about a product not recipe, I will answer with the definition and benefits of the requested product in the following format:
Topic:
Definition:
Request type:
Directions:



For Recipe-related Reqeust type : REQUEST_RECIPE_INFO
For Product-related Request type : REQUEST_PRODUCT_INFO
Recipe-related keyword :  'recipe', 'recipes', 'cooking', 'cook', 'cooking' and related words
Product-related keyword : 'product', 'products', 'grocery', 'groceries', 'grocery product','what is ,
For recipe-lated use value Request type : REQUEST_RECIPE_INFO and for product-related (product definition)  use value Request type : REQUEST_PRODUCT_INFO
I hope these rules are easy to understand and will help me provide better assistance to users with their recipe and grocery product inquiries.


" . $clientRequest;
        $response = Http::post('http://localhost:5001/api/chat', [
            'message' => $rules
        ]);

        $text = $response->getBody()->getContents();

        // STRINGIFY


        $startPos = strpos($text, 'Request type:');
        $endPos = strpos($text, 'Directions:', $startPos);

        // Potong teks Request type beserta isinya
        $requestTypeText = substr($text, $startPos, $endPos - $startPos);
        $requesttype = explode(": ", $requestTypeText)[1];


        // Hapus teks Request type beserta isinya dari respons  e
        $text = str_replace($requestTypeText, '', $text);
        $textToClient = json_decode($text, true);
        $parseProduct = strtr($text, 'Ingredients', true);

        //parse type_request



        // pencarian list_product
        $posAwalProduct = strpos($text, 'Ingredients') + strlen('Product[list_product]: ');
        $posAkhirTitik = strpos($text, '.', $posAwalProduct);
        $teksListProduct = substr($text, $posAwalProduct, $posAkhirTitik - $posAwalProduct);


        // Parse nama, ingredients, dan directions
        $namaStartPos = strpos($text, 'Name:') + strlen('Name: ');
        $namaEndPos = strpos($text, 'Ingredients:');
        $nama = trim(substr($text, $namaStartPos, $namaEndPos - $namaStartPos));

        $ingredientsStartPos = strpos($text, 'Ingredients:') + strlen('Ingredients: ');
        $ingredientsEndPos = strpos($text, 'Directions:');
        $ingredients = trim(substr($text, $ingredientsStartPos, $ingredientsEndPos - $ingredientsStartPos));

        $directionsStartPos = strpos($text, 'Directions:') + strlen('Directions: ');
        $directions = trim(substr($text, $directionsStartPos));

        // pencarian list_product
        $posAwalProduct = strpos($text, 'Ingredients') + strlen('Product[list_product]: ');
        $posAkhirTitik = strpos($text, '.', $posAwalProduct);
        $teksListProduct = substr($text, $posAwalProduct, $posAkhirTitik - $posAwalProduct);

        //pencarian type_request

        $listProduct = explode(',', $teksListProduct);
        //delete space

        for ($i = 0; $i < count($listProduct); $i++) {
            $listProduct[$i] = trim($listProduct[$i]);
        }
        // search listproduct[] with laravel scout with loop
        $products = [];

        foreach ($listProduct as $productName) {
            // Search product with SQL query
            $product = Product::where('name', 'like', '%' . $productName . '%')
                ->orWhere('description', 'like', '%' . $productName . '%')
                ->first();

            if (!$product) {
                continue; // Skip to next iteration if product is not found
            }

            // Add new field if discount_id is not null
            if ($product->discount_id !== null) {
                $discount = Discount::find($product->discount_id);
                $product->discount_percentage = $discount ? $discount->discount_percetage : 0;
                $product->now_price = $discount
                    ? $product->price - ($product->price * $discount->discount_percetage / 100)
                    : $product->price;
            } else {
                $product->discount_percentage = 0;
                $product->now_price = $product->price;
            }

            $products[] = $product;
        }



        //get recipe tutorial from youtube

        // Inisialisasi Google_Client
        $client = new Google_Client();
        $client->setApplicationName('Recipe  Youtube Search');
        $client->setDeveloperKey(env('GOOGLE_DEVELOPER_KEY')); // Masukkan API Key Anda

        // Inisialisasi Google_Service_YouTube
        $youtube = new Google_Service_YouTube($client);
        if ($requesttype == "REQUEST_RECIPE_INFO \\n") {

            // Cek apakah limit kouta sudah penuh
            try {
                $quota = $client->getHttpClient()->get('https://www.googleapis.com/youtube/v3/search?part=snippet&q=test&key=' . env('GOOGLE_DEVELOPER_KEY'))->getBody()->getContents();
                $quota = json_decode($quota, true);
                if (isset($quota['error']['code']) && $quota['error']['code'] == 403 && $quota['error']['errors'][0]['reason'] == 'quotaExceeded') {
                    $youtubeData = null; // Jika kouta sudah penuh, set nilai $youtubeData menjadi null
                } else {
                    // Inisialisasi Google_Service_YouTube
                    $youtube = new Google_Service_YouTube($client);

                    // Buat objek search list
                    $searchResponse = $youtube->search->listSearch('id,snippet', array(
                        'q' => $clientRequest,
                        'maxResults' => 1, // Jumlah video yang ingin ditampilkan
                        'type' => 'video',
                        'order' => 'relevance', // Urutkan berdasarkan relevansi
                    ));

                    // Tampilkan hasil pencarian
                    foreach ($searchResponse['items'] as $searchResult) {
                        $videoId = $searchResult['id']['videoId'];
                        $title = $searchResult['snippet']['title'];
                        $description = $searchResult['snippet']['description'];
                        $thumbnail = $searchResult['snippet']['thumbnails']['default']['url'];
                        $publishedAt = $searchResult['snippet']['publishedAt'];
                        $channelId = $searchResult['snippet']['channelId'];
                        $channelTitle = $searchResult['snippet']['channelTitle'];

                        // Tampilkan informasi video yang ditemukan
                    }

                    $youtubeData = [
                        'videoId' => $videoId,
                        'title' => $title,
                        'description' => $description,
                        'thumbnail' => $thumbnail,
                        'publishedAt' => $publishedAt,
                        'channelId' => $channelId,
                        'channelTitle' => $channelTitle
                    ];
                }
            } catch (Exception $e) {
                $youtubeData = null; // Jika terjadi error, set nilai $youtubeData menjadi null
            }
        }

        // format response
        if ($requesttype == "REQUEST_RECIPE_INFO \\n") {
            $format = [
                'list_product' => $products,
                'text' => $textToClient['answer'],
                'video' => $youtubeData,
                'requesttype' => 'REQUEST_RECIPE_INFO',
                'recipe_name' => $nama,
                'ingredients' => $ingredients,
                'directions' => $directions,


            ];
        } else {
            $format = [
                'list_product' => $products,
                'text' => $textToClient['answer'],
                "video" => null,
                'requesttype' =>  'REQUEST_PRODUCT_INFO',
            ];
        }



        return ResponseFormatter::success($format, 'Berhasil mendapatkan product ');
    }

    public function SearchByImage(Request $request)
    {
        $file = $request->file('file');

        $client = new Client(['base_uri' => env('IMAGE_SEARCH_SERVICE')]);
        $response = $client->request('POST', 'image-classification', [
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => fopen($file, 'r'),
                ]
            ]
        ]);

        $response = json_decode($response->getBody()->getContents(), true);

        $picName = $response['body']['auctions'][0]['picName'];
        $score = $response['body']['auctions'][0]['score'];

        //convert to 1-100%
        $score = $score * 100;



        $products = Product::search($picName)->get();


        $response = $products->map(function ($product) {
            $data = $product->toArray();



            if ($product->discount_id) {
                $discount = discount::find($product->discount_id);
                $data['discount_percentage'] = $discount->discount_percetage;
                $data['now_price'] = $product->price - ($product->price * $discount->discount_percetage / 100);
            } else {
                $data['discount_percentage'] = 0;
                $data['now_price'] = $product->price;
            }




            return $data;
        });

        // Lakukan sesuatu dengan $products

        return response()->json([
            'success' => true,
            'data' => $response,
            'score' => $score
        ]);
    }

    private function createSearchVector($ingredients)
    {
        $searchVector = [];
        $searchWords = explode(',', $ingredients);

        foreach ($searchWords as $word) {
            $searchVector[$word] = 1;
        }

        return $searchVector;
    }

    private function createProductVector($description)
    {
        $productVector = [];
        $productWords = explode(' ', $description);

        foreach ($productWords as $word) {
            $productVector[$word] = 1;
        }

        return $productVector;
    }

    private function cosineSimilarity2($vectorA, $vectorB)
    {
        $dotProduct = 0;
        $normA = 0;
        $normB = 0;

        foreach ($vectorA as $key => $value) {
            if (isset($vectorB[$key])) {
                $dotProduct += $value * $vectorB[$key];
            }

            $normA += $value * $value;
        }

        foreach ($vectorB as $key => $value) {
            $normB += $value * $value;
        }

        if ($normA == 0 || $normB == 0) {
            return 0;
        }

        return $dotProduct / (sqrt($normA) * sqrt($normB));
    }


    function storeUserRecipe(Request $request) {
        $user = auth()->user();


        $recipe = new user_save_recipe();

        $recipe->user_id = $user->id;
        $recipe->name = $request->name;
        $recipe->description = $request->description;
        $recipe->ingredients = $request->ingredients;
        $recipe->steps = $request->steps;
        $recipe->image = $request->image;

        $recipe->save();

        return ResponseFormatter::success($recipe, 'Berhasil menyimpan resep');

    }

    function getUserRecipe(Request $request) {
        $user = auth()->user();

        $recipe = user_save_recipe::where('user_id', $user->id)->get();

        return ResponseFormatter::success($recipe, 'Berhasil mendapatkan resep');
    }
}
