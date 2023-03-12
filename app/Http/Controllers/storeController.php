<?php

namespace App\Http\Controllers;

use App\Helper\ResponseFormatter;
use App\Models\location;
use App\Models\store;
use Illuminate\Http\Request;

class storeController extends Controller
{

    public function index() {
        $stores =store::with('location')->get();
        return view('pages.store.index')->with('stores', $stores);


    }

    public function create() {
        $locations = location::all();
        return view('pages.store.create')->with('locations', $locations);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:store',
            'location_id' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        store::create([
            'name' => $request->name,
            'location_id' => $request->location_id,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('store.index')->with('success', 'warehouse ditambahkan ');;
    }



}
