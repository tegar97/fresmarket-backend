<?php

namespace App\Http\Controllers;

use App\Helper\ResponseFormatter;
use App\Models\cartItemsModel;
use App\Models\cartsModel;
use App\Models\productModel;
use Illuminate\Http\Request;
use Auth;

class cartController extends Controller
{
    public function create(Request $request) {
        $user = Auth::user();
        if($user['id'] == null) {
            return ResponseFormatter::error(['message' => "please login to continue"],500);
        }else{
            $products = productModel::where('id',$request->products_id)->first();
            if($products === null) {
                return ResponseFormatter::error(['message' => "product not found"], 404);

            }
            $checkproduct = cartItemsModel::where('products_id', $products['id'])->first();
            $cart = cartsModel::where('id', $user['carts_id'])->first();

            if($checkproduct === null) {
                $cartItem = cartItemsModel::create([
                    'products_id' => $products['id'],
                    'carts_id' => $user['carts_id'],
                    'qty' => $request->qty,
                    'total' => $products['price'] * $request->qty
                ]);



                $cart['total'] = $cart['total'] + $cartItem['total'];
                $cart->save();



            }else{
              

                $checkproduct['qty'] =  $checkproduct['qty'] + $request->qty;
                $checkproduct['total'] = $checkproduct['total'] + ($products['price'] * $request->qty);
                $checkproduct->save();



                $cart['total'] = $cart['total'] + $checkproduct['total'];
                $cart->save();


            }
          
         
            return ResponseFormatter::success(['message' => "Berhasil"], 200);


            
        }
    }
}
