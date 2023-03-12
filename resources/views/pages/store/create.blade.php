@extends('layouts.default')
@section('title', 'Add Outlet')

@section('content')
    @if(session()->has('errors'))
    <div class="alert alert-danger   ">
        {{ session()->get('errors') }}
    </div>
@endif
    <div class="content bg-white p-2 px-4">
        <div class=" mt-5">
            <form enctype="multipart/form-data" action="{{ route('store.store') }}" method="post">
                @csrf
                <div class="form-group ">
                    <label for="exampleInputEmail1">Nama Gudang</label>
                    <input type="text" class="form-control" name="name" placeholder="Nama Kota">
                </div>
                <div class="form-group  mt-3  ">
                    <label for="exampleInputEmail1">Kota</label>
                    <select class="form-control" id="position-option" name="location_id">
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->city }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mt-3 ">
                    <label for="search">Cari lokasi:</label>
                    <input type="text" class="form-control" id="search">
                </div>

                <div id="map" style="height: 300px; width: 100%;" class=""></div>
                <div class="form-group mt-3">
                    <label for="address">Address:</label>
                    <input type="text" class="form-control" id="address" name="address">

                </div>
                <div class="form-group mt-3">
                    <label for="latitude">Latitude:</label>
                    <input type="text" class="form-control" id="latitude" name="latitude">
                </div>

                <div class="form-group mt-3 mb-3">
                    <label for="longitude">Longitude:</label>
                    <input type="text" class="form-control" id="longitude" name="longitude">
                </div>




                <button type="submit" class="btn btn-green text-white ">Tambah Gudang</button>
            </form>
        </div>
    </div>
@stop
