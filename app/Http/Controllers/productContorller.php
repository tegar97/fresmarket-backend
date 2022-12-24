<?php

namespace App\Http\Controllers;

use App\Helper\imageResizer;
use App\Helper\ResponseFormatter;
use App\Models\categoryModel;
use App\Models\productModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class productContorller extends Controller
{
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:50'],
            'image' => ['required'],
            'categories_id' => ['required'],
            'description' => ['required'],
            'product_type' => ['required'],
            'product_exp' => ['required'],
            'product_calori' => ['required'],
            'weight' => ['required'],
            'price' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $validator->errors(),
                ],
                401
            );
        }
        $category = categoryModel::where("id",$request->categories_id)->first();

        if($category !== null) {
            if ($image = $request->file('image')) {
                $getImageName = imageResizer::ResizeImage($image, 'product', 'product', 300, 300);

                //store your file into directory and db




                $products = productModel::create([
                    'name' => $request->name,
                    'image' => $getImageName,
                    'categories_id' => $category['id'],
                    'description' => $request->description,
                    'product_type' => $request->product_type,
                    'product_exp' => $request->product_exp,
                    'product_calori' => $request->product_calori,
                    'weight' => $request->weight,
                    'price' => $request->price,

                ]);
            } else {
                $products = productModel::create(['name' => $request->name,
                    'image' => 'defaultProduct.png',
                    'categories_id' => $category['id'],
                    'description' => $request->description,
                    'product_type' => $request->product_type,
                    'product_exp' => $request->product_exp,
                    'product_calori' => $request->product_calori,
                    'weight' => $request->weight,
                    'price' => $request->price,
                ]);
            }

            return response()->json($products);

        }else{
            return ResponseFormatter::error(["message" => "Category id not found"]);
        }


    }

    public function all(Request $request)
    {

        $category = $request->query('category_id');

        if($category) {
            $products = productModel::where('categories_id',$category)->get();
        }else{
            $products = productModel::all();

        }

        return ResponseFormatter::success($products, 'Berhasil mendapatkan data product');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $products = productModel::where('name', 'like', "%$query%")
        ->orWhere('description', 'like', "%$query%")
        ->get();



        return ResponseFormatter::success($products, 'Berhasil mendapatkan data product');

    }

    public function productCity(Request $request) {
        $product = $productId;
        $city = $request->city;


    }
}
