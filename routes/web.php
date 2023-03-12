<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\productGroupController;
use App\Http\Controllers\recipeController;
use App\Http\Controllers\storeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('categories', CategoriesController::class);
Route::resource('locations', LocationController::class);
Route::resource('store', storeController::class);
Route::resource('products', ProductController::class);
Route::resource('product-groups', productGroupController::class);
Route::get('ProductDiscount', [ProductController::class, 'productDiscount'])->name('productDiscount');
Route::resource('discounts', DiscountController::class);
Route::get('recipe', [recipeController::class, 'index'])->name('recipe.index');
