<?php

namespace App\Http\Controllers;

use App\Helper\ResponseFormatter;
use App\Models\City;
use App\Models\productModel;
use App\Models\TagProduct;
use App\Models\Tags;
use Illuminate\Http\Request;

class TagProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Tags::with('products')->whereHas('products.cities',function($query) use ($request) {
            $city =
                City::where('name', $request->name)->first();
            
            $query->where('city_id', $city->id);

        })->get();

        return ResponseFormatter::success($products, 'Berhasil mengambil data product');


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $product = productModel::find($request->product_id);
        $tags = Tags::find($request->tag_id);
        if(!$product || !$tags){
            return ResponseFormatter::error(null, 'Product or Tag not found', 404);
        }

        $TagProduct = TagProduct::create([
            'product_id' => $product->id,
            'tags_id' => $tags->id,
        ]);


        return ResponseFormatter::success($TagProduct, 'Berhasil menambahkan tag baru');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TagProduct  $tagProduct
     * @return \Illuminate\Http\Response
     */
    public function show(TagProduct $tagProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TagProduct  $tagProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(TagProduct $tagProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TagProduct  $tagProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TagProduct $tagProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TagProduct  $tagProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(TagProduct $tagProduct)
    {
        //
    }
}
