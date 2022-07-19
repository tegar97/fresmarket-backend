<?php

namespace App\Http\Controllers;

use App\Helper\ResponseFormatter;
use App\Models\store;
use Illuminate\Http\Request;

class storeController extends Controller
{
    public function create(Request $request) {
        $store = store::create([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude 
        ]);

        return ResponseFormatter::success($store);
    }
}
