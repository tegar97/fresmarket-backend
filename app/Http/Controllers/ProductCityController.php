<?php

namespace App\Http\Controllers;

use App\Helper\ResponseFormatter;
use App\Models\City;
use App\Models\location;
use App\Models\product;
use App\Models\ProductCity;
use App\Models\productModel;
use Illuminate\Http\Request;

class ProductCityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $product = product::find($request->product_id);
        $city = location::find($request->city_id);

        $productCity =ProductCity::create(
            [
                'product_id' => $product->id,
                'city_id' => $city->id,
            ]
            );


        return ResponseFormatter::success($productCity, 'Berhasil menambahkan kota baru');
    }

    public function checkCity(Request $request){
        $cityName = $request->query('city_name');


        $city = location::where('city', $cityName)->first();

        if ($city != null) {
            return ResponseFormatter::success(true,'tersedia');
        }else{
            return ResponseFormatter::success(false, 'tidak tersedia');
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function getProductByCity(Request $request) {


        // $city = City::find($request->city_id);

        // if (!$city) {
        //     return ResponseFormatter::error(null, 'City not found', 404);
        // }

        // // $products = ProductCity::with(['cities' => function ($query) {
        // //     $query->where('city_id', 1);
        // // }])
        // //     ->get();
        $category = $request->query('category_id');
        $cityName = $request->query('city_name');
        // $products = ProductCity::with('products')->where('city_id',$request->city_id)->get();

        // if ($products->isEmpty()) {
        //     return ResponseFormatter::error(null, 'No products available in this city', 404);
        // }

        // return ResponseFormatter::success($products, 'Successfully retrieved products based on city');

        $city = City::where('name', $cityName)->first();

        if (!$city) {
            return response()->json([
                'error' => 'City not found'
            ], 404);
        }



        // $products = productModel::with('cities')->where('cities.city_id', $request->city_id)->get();
        // $products = productModel::with(['cities' => function ($query) use ($request) {
        //     $query->where('city_id', $request->city_id);
        // }])
        //     ->get();


        if($category) {
            $products = product::whereHas('cities', function ($query) use ($request) {
                $city =
                    City::where('name', $request->query('city_name'))->first();
                $query->where('city_id', $city->id);
            })->where('categories_id',$category)->get();

        }else{
            $products = product::whereHas('cities', function ($query) use ($request) {
                $city =
                    City::where('name', $request->query('city_name'))->first();
                $query->where('city_id', $city->id);
            })->get();

        }


      return ResponseFormatter::success($products,'berhasil');





    }


    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductCity  $productCity
     * @return \Illuminate\Http\Response
     */
    public function show(ProductCity $productCity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductCity  $productCity
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductCity $productCity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductCity  $productCity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductCity $productCity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductCity  $productCity
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCity $productCity)
    {
        //
    }
}
