<?php

namespace App\Http\Controllers;

use App\Models\discount;
use App\Models\product;
use App\Models\ProductCity;
use Illuminate\Http\Request;

class DiscountController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([

            'discount_percentage' => 'required|numeric|between:1,99', // nilai persentase harus lebih dari 0 dan kurang dari 100
            'products' => 'required|array|min:1', // minimal harus memilih 1 product
            'products.*' => 'integer|exists:products,id' // id product harus ada di dalam tabel products
        ]);

        $discount = new Discount([

            'discount_percetage' => $request->get('discount_percentage'),
        ]);

        $discount->save();

        // $products = product::whereIn('id', $request->get('products'))->get();

        foreach ($request->input('products', []) as $product_id) {
            $product = product::find($product_id);

            if ($product) {
                // Tambahkan diskon pada produk
                $product->discount_id = $discount->id;
                $product->save();
            }
        }



        return redirect('/products')->with('success', 'Discount has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function show(discount $discount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function edit(discount $discount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, discount $discount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy(discount $discount)
    {
        //
    }
}
