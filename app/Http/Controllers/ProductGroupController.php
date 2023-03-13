<?php

namespace App\Http\Controllers;

use App\Models\group_product;
use App\Models\groupProduct;
use App\Models\product;
use App\Models\product_group;
use App\Models\productGroup;
use Illuminate\Http\Request;

class ProductGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $productGroups = product_group::with('groupProducts')->get();
        $products = product::all();

        return view('pages.product_groups.index')->with('productGroups', $productGroups)->with('products', $products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $products = product::all();

        return view('pages.product_groups.create')->with('products', $products);
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
            'title' => 'required',
            'description' => 'required',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
        ]);

        $productGroup = product_group::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);


        foreach ($validatedData['products'] as $product) {
            $groupProduct = new group_product();
            $groupProduct->product_id = $product;
            $groupProduct->product_group_id  =  $productGroup->id;
            $groupProduct->save();
        }




        return redirect()->route('product-groups.index')
            ->with('success', 'Product group created successfully.');
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

        $productGroup = product_group::with('groupProducts')->find($id);

        $products = product::all();

        return view('pages.product_groups.edit')->with('productGroup', $productGroup)->with('products', $products);
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
        $productGroup = product_group::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
        ]);


        $productGroup->title = $request->input('title');
        $productGroup->description = $request->input('description');
        $productGroup->save();

        $productGroup->groupProducts()->delete();

        foreach ($validatedData['products'] as $product) {
            $groupProduct = new group_product();
            $groupProduct->product_id = $product;
            $groupProduct->product_group_id  =  $productGroup->id;
            $groupProduct->save();
        }

        return redirect()->route('product-groups.index')
            ->with('success', 'Product group updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productGroup = product_group::find($id);
        $productGroup->groupProducts()->delete();
        $productGroup->delete();

        return redirect()->route('product-groups.index');
    }
}
