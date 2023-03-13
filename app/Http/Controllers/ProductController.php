<?php

namespace App\Http\Controllers;

use App\Helper\imageResizer;
use App\Http\Resources\locations;
use App\Models\Category;
use App\Models\categoryModel;
use App\Models\Location;
use App\Models\product;
use App\Models\product_location;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $location_id = $request->input('location_id');
        $products = $location_id   ?  Product::with('locations')
            ->whereHas('locations', function ($query) use ($location_id) {
                $query->where('location_id', $location_id);
            })->get() : Product::with('locations')->get();
        $locations = location::all();



        return view('pages.products.index')->with('products', $products)->with('locations', $locations);
    }

    public function productDiscount()
    {
        $productDiscount = product::with('locations')->with('discount')->where('discount_id', '!=', null)->get();
        $products = product::with('locations')->with('discount')->get();
        $TotalPrice = 0;
        $discount = 0;
        foreach ($products as $product) {
            if ($product->discount_id != null) {
                $TotalPrice = $product->price - ($product->price * $product->discount->discount_percetage / 100);
                $discount = $product->discount->discount_percetage;
            }
        }
        return view('pages.products.product_discount')->with('products', $products)->with('TotalPrice', $TotalPrice)->with('discount', $discount)->with('productDiscount', $productDiscount);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        $categories = categoryModel::all();
        $locations = location::all();
        return view('pages.products.create', compact('categories', 'locations'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi data yang diterima dari form input
        $validatedData = $request->validate([
            'name' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'weight' => 'required|numeric',
            'product_type' => 'required|string',
            'product_calori' => 'required|numeric',
            'categories_id' => 'required|exists:categories,id',
            'locations' => 'required|array',
            'locations.*' => 'exists:locations,id',
        ]);


        // Upload gambar produk ke dalam direktori public/images/products
        $image = $request->file('image');
        $getImageName = imageResizer::ResizeImage($image, 'images', 'images', 80, 80);
        // convert to webp
        $imageWebpThumb = imageResizer::ResizeImage($image, 'images', 'images', 80, 80 , 'webp',90);

        // convert web image_big
        $imagWebpBig = imageResizer::ResizeImage($image, 'images', 'images', 500, 500 , 'webp',90);
        // Simpan produk ke dalam database
        $product = new Product;
        $product->name = $validatedData['name'];
        $product->image = $getImageName;
        $product->image_web = $getImageName;
        $product->image_web_big = $imagWebpBig;
        $product->description = $validatedData['description'];
        $product->price = $validatedData['price'];
        $product->weight = $validatedData['weight'];
        $product->product_type = $validatedData['product_type'];
        $product->product_calori = $validatedData['product_calori'];
        $product->categories_id = $validatedData['categories_id'];
        $product->product_exp = '1 week';






        $product->save();
        // Request post ke api localhost:3000/upload dengan req body file
        $client = new Client(['base_uri' =>env('IMAGE_SEARCH_SERVICE')]);
        $response = $client->request('POST', 'upload', [
            'headers' => [
                'Accept' => 'application/json',
            ],

            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($image, 'r'),
                    'filename' => $imagWebpBig
                ],
                [
                    'name' => 'product_id',
                    'contents' => $product->id
                ],
                [
                    'name' => 'product_name',
                    'contents' => $product->name
                ]

            ]
        ]);

        // Simpan informasi lokasi produk ke dalam database
        foreach ($validatedData['locations'] as $location) {
            $productLocation = new product_location();
            $productLocation->product_id = $product->id;
            $productLocation->location_id = $location;
            $productLocation->save();
        }
        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $product = product::find($id);
        $categories = categoryModel::all();
        $locations = location::all();
        return view('pages.products.edit', compact('product', 'categories', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'weight' => 'required|numeric',
            'product_type' => 'required|string',
            'product_calori' => 'required|numeric',
            'categories_id' => 'required|exists:categories,id',
            'locations' => 'required|array',
            'locations.*' => 'exists:locations,id',
        ]);

        $product = Product::findOrFail($id);

        // Jika ada gambar produk yang diupload, maka simpan gambar baru
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/products'), $imageName);
            $product->image = $imageName;
        }

        // Update informasi produk ke dalam database
        $product->name = $validatedData['name'];
        $product->description = $validatedData['description'];
        $product->price = $validatedData['price'];
        $product->weight = $validatedData['weight'];
        $product->product_type = $validatedData['product_type'];
        $product->product_calori = $validatedData['product_calori'];
        $product->categories_id = $validatedData['categories_id'];
        $product->save();

        // Update informasi lokasi produk ke dalam database
        $productLocations = $product->product_locations;
        $existingLocations = array();
        foreach ($productLocations as $productLocation) {
            $existingLocations[] = $productLocation->location_id;

            if (!in_array($productLocation->location_id, $validatedData['locations'])) {
                // Hapus data lokasi produk dari tabel product_location
                $productLocation = product_location::where('product_id', $product->id)->where('location_id', $productLocation->location_id)->first();

                $productLocation->delete();
            }
        }
        foreach ($validatedData['locations'] as $location) {
            if (!in_array($location, $existingLocations)) {
                $productLocation = new product_location();
                $productLocation->product_id = $product->id;
                $productLocation->location_id = $location;
                $productLocation->save();
            }
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = product::find($id);

        // Hapus gambar produk dari direktori public/images/products
        $image_path = public_path('images/products/' . $product->image);

        // Delete relationship data in product_location table
        $product->locations()->detach();


        $product->delete();
    }
}
