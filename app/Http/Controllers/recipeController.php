<?php

namespace App\Http\Controllers;

use App\Helper\imageResizer;
use App\Helper\ResponseFormatter;
use App\Models\productModel;
use App\Models\recipeItemModel;
use App\Models\recipeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class recipeController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:50'],
            'description' => ['required'],
            'image' => ['required'],
            'calori' => ['required'],
            'estimate_time' => ['required']
         
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
        $image = $request->file('image');
        $getImageName = imageResizer::ResizeImage($image, 'recipe', 'recipe', 300, 300);

        //store your file into directory and db




        $recipe = recipeModel::create([
        'title' => $request->title,
        'description' => $request->description,
        'image' => $getImageName,
        'calori' => $request->calori,
        'level' => $request->level,
         'estimateTime' => $request->estimate_time,
            'step' => $request->step,


        ]);
        return ResponseFormatter::success($recipe, 'berhasil');

        

    }

    public function addIgredient(Request $request) {
        $validator = Validator::make($request->all(), [
            'qty' => ['required'],
            'products_id' => ['required'],
           

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
        $product = productModel::where("id", $request->products_id)->first();
        if($product != null) {
            $recipe = recipeItemModel::create([
                'qty' => $request->qty,
                'products_id' => $request->products_id,
                'recipe_id' => $request->recipe_id,

            ]);
            return ResponseFormatter::success($recipe, 'berhasil');

        }else{
            return ResponseFormatter::error('Product id tidak ditemukan');

        }

      
    }
    public function getRecipe(){
        $recipe = recipeModel::with('recipeItem.product')->select('title','image','description','calori','level','estimateTime','id', 'step')->get();
        return ResponseFormatter::success($recipe, 'berhasil');

    }
}
