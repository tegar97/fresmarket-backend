<?php

namespace App\Http\Controllers;

use App\Http\Resources\locations;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = location::all();
        return view('pages.locations.index')->with('locations', $locations);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.locations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'city' => 'required|unique:locations',
            'province' => 'required',

        ]);

        Location::create([
            'city' => $request->city,
            'province' => $request->province,
        ]);

        return redirect()->route('locations.index')->with('success', 'lokasi ditambahkan ');;
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

        $location = location::find($id);
        return view('pages.locations.edit')->with('location', $location);
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

        $request->validate([
            'city' => 'required|unique:locations',
            'province' => 'required',


        ]);

        $location = location::find($id);

        $location->update([
            'city' => $request->city,
            'province' => $request->province,
        ]);

        return redirect()->route('locations.index')->with('success', 'lokasi diupdate ');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        Location::where('id', $id)->delete();

        return redirect()->route('locations.index')->with('success', 'lokasi dihapus ');
    }
}
