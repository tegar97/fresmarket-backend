<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\cartController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\orderController;
use App\Http\Controllers\ProductCityController;
use App\Http\Controllers\productContorller;
use App\Http\Controllers\ProductViewHistoryController;
use App\Http\Controllers\recipeController;
use App\Http\Controllers\SearchHistoryController;
use App\Http\Controllers\storeController;
use App\Http\Controllers\TagProductController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\userAddressController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VoucherController;
use App\Models\ProductCity;
use App\Models\TagProduct;
use App\Models\transaction;
use App\Models\userAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'v1'], function () {

    /// === Auth ==== ///
    Route::post('login', [UsersController::class,'login'])->name("login");
    Route::post('register', [UsersController::class,'register']);


    Route::post('store', [storeController::class, 'create']);

    /// === Category ==== ///
    Route::post('category', [CategoriesController::class,'create']);
    Route::get('category', [ApiController::class, 'getCategory']);

    /// === Product ==== ///
    Route::post('product', [productContorller::class, 'create']);
    // Route::get('product', [productContorller::class, 'all']);
    Route::get('products', [ApiController::class, 'getProductByCity']);
    Route::get('productsGroup', [ApiController::class, 'getProductGroupByCity']);
    Route::get('productsGroup/{slug}', [ApiController::class, 'getProductGroupByCityDetail']);
    Route::get('product/{slug}', [ApiController::class, 'getDetailProduct']);

    Route::get('/products/search',[productContorller::class, 'search']);

    /// === Payment ==== ///
    Route::post('webHookHandler', [orderController::class, 'webHookHandler']);
    Route::get('payment-code', [orderController::class, 'paymentCode']);

    Route::post('recipe', [recipeController::class, 'create']);
    Route::post('recipe-item', [recipeController::class, 'addIgredient']);
    Route::get('recipe', [recipeController::class, 'getRecipe']);
    Route::get('showCategoryWithProduct', [CategoriesController::class, 'showCategoryWithProduct']);


    // Voucher

    Route::post('voucher', [VoucherController::class, 'create']);
    Route::get('getAvailableVoucher', [VoucherController::class, 'getAvailableVoucher']);

    //city
    Route::get('city', [CityController::class, 'index']);
    Route::post('city', [CityController::class, 'create']);
    Route::post('productCity', [ProductCityController::class, 'create']);
    Route::get('productCity', [ProductCityController::class, 'getProductByCity']);
    Route::get('checkCity', [ProductCityController::class, 'checkCity']);

    //tags
    Route::get('tags', [TagsController::class, 'index']);
    Route::post('tags', [TagsController::class, 'create']);
    Route::post('tagProducts', [TagProductController::class, 'create']);
    Route::get('tagProducts', [TagProductController::class, 'index']);

    Route::get('/search-video', function () {
        $query = 'resep makanan nasi goreng';
        app('App\Http\Controllers\YoutubeController')->searchVideo($query);
    });

    Route::post('editStatusTransaksi', [orderController::class, 'editTransaction']);

    Route::post('chef-ai', [ApiController::class, 'searchRelevantIngredient']);

    Route::post('SearchByImage', [ApiController::class, 'SearchByImage']);

    Route::group(['middleware' => 'auth:api'], function () {


        Route::get('getNearOutlet', [UsersController::class, 'getNearOutlet']);
        Route::get('getAllOutlet', [UsersController::class, 'getAllListOutlet']);

        Route::get ('getMe', [UsersController::class, 'getMe']);
        Route::post ('logout', [UsersController::class, 'logout']);
        Route::post('cart', [cartController::class, 'create']);

        Route::post('address', [userAddressController::class, 'create']);
        Route::get('address', [userAddressController::class, 'getMyAddress']);
        Route::get('mainAddress', [userAddressController::class, 'getMainAddress']);
        Route::post('ChangeMainAddress', [userAddressController::class, 'ChangeMainAddress']);
        Route::post('order/mandiri', [orderController::class, 'create']);
        Route::post('order/bca', [orderController::class, 'bca']);
        Route::post('order/indomaret', [orderController::class, 'indomaret']);
        Route::post('order/snap', [orderController::class, 'snapUrl']);
        Route::get('get-my-payment', [orderController::class, 'getMyPayment']);


        Route::get('transaction', [orderController::class, 'getTransaction']);


        Route::post('claim-voucher', [VoucherController::class, 'claimVoucher']);
        Route::get('my-voucher', [VoucherController::class, 'getMyVoucher']);
        Route::post('check-voucher', [VoucherController::class, 'checkValidVoucher']);
        Route::post('apply-voucher', [VoucherController::class, 'useVoucher']);


        // search
        Route::post('saveHistory', [SearchHistoryController::class, 'store']);

        Route::post('viewHistory', [ProductViewHistoryController::class, 'store']);

        Route::get('/myrecommendation', [ApiController::class, 'getSimilarProduct']);


        Route::get('/getMostVisitProduct', [ApiController::class, 'getMostVisitProduct']);
        Route::get('/recommendRecipes', [ApiController::class, 'recommendRecipes']);

    });

});
