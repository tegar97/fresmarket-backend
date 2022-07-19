<?php

namespace App\Http\Controllers;

use App\Helper\imageResizer;
use App\Helper\ResponseFormatter;
use App\Models\categoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:50'],
            'icon' => ['required'],
            'bgColor' => ['required', 'string',],
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

        if ($image = $request->file('icon')) {
            $getImageName = imageResizer::ResizeImage($image, 'icon', 'icon', 40, 40);

            //store your file into directory and db


            $categories = categoryModel::create([
                'name' => $request->name,
                'icon' => $getImageName,
                'bgColor' => $request->bgColor,
            
            ]);
        } else {
            $categories = categoryModel::create([
                'name' => $request->name,
                'icon' => 'defaultIcon.png',
                'bgColor' => $request->bgColor,
            ]);
        }

        return response()->json($categories);

    }
    public function showCategoryWithProduct(){
        $categories = categoryModel::with("products")->select('id', 'name','icon')->get();
        return ResponseFormatter::success($categories, 200);

    }

    public function all() {
        $categories = categoryModel::all();

        return ResponseFormatter::success($categories, 200);


    }
}
