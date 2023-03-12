<?php

namespace App\Http\Controllers;

use App\Models\ProductViewHistory;
use Illuminate\Http\Request;

class ProductViewHistoryController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        $productId = $request->input('product_id');

        // Buat record baru untuk product view history
        $productViewHistory = new ProductViewHistory([
            'user_id' => $user->id,
            'product_id' => $productId
        ]);
        $productViewHistory->save();

        return response()->json(['success' => true]);
    }
}
