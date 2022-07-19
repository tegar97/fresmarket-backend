<?php

use App\Http\Controllers\cartController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\orderController;
use App\Http\Controllers\productContorller;
use App\Http\Controllers\recipeController;
use App\Http\Controllers\storeController;
use App\Http\Controllers\userAddressController;
use App\Http\Controllers\UsersController;
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
    Route::get('category', [CategoriesController::class, 'all']);

    /// === Product ==== ///
    Route::post('product', [productContorller::class, 'create']);
    Route::get('product', [productContorller::class, 'all']);
    /// === Payment ==== ///
    Route::post('webHookHandler', [orderController::class, 'webHookHandler']);
    Route::get('payment-code', [orderController::class, 'paymentCode']);
    
    Route::post('recipe', [recipeController::class, 'create']);
    Route::post('recipe-item', [recipeController::class, 'addIgredient']);
    Route::get('recipe', [recipeController::class, 'getRecipe']);
    Route::get('showCategoryWithProduct', [CategoriesController::class, 'showCategoryWithProduct']);

    Route::group(['middleware' => 'auth:api'], function () {

        Route::get('getNearOutlet', [UsersController::class, 'getNearOutlet']);
        Route::get('getAllOutlet', [UsersController::class, 'getAllListOutlet']);

        Route::get ('getMe', [UsersController::class, 'getMe']);
        Route::post('cart', [cartController::class, 'create']);

        Route::post('address', [userAddressController::class, 'create']);
        Route::get('address', [userAddressController::class, 'getMyAddress']);
        Route::get('mainAddress', [userAddressController::class, 'getMainAddress']);
        Route::post('ChangeMainAddress', [userAddressController::class, 'ChangeMainAddress']);
        Route::post('order/mandiri', [orderController::class, 'create']);
        Route::post('order/bca', [orderController::class, 'bca']);
        Route::post('order/indomaret', [orderController::class, 'indomaret']);
        Route::post('order/indomaret', [orderController::class, 'indomaret']);
       
       
        Route::get('transaction', [orderController::class, 'getTransaction']);
      
      

    });
   
});