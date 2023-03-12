@extends('layouts.default')
@section('title','Add location')

@section('content')
<div class="content bg-white p-2 px-4">
    <div class=" mt-5">
        <form enctype="multipart/form-data" action="{{route('locations.store')}}" method="post">

            @csrf
            <div class="form-group ">
                <label for="exampleInputEmail1">Nama kota</label>
                <input type="text" class="form-control" name="city"  placeholder="Nama Kota">
            </div>
             <div class="form-group  mt-3 mb-5 ">
                <label for="exampleInputEmail1">Provinsi</label>
                <input type="text" class="form-control" name="province" placeholder="Nama Provinsi">
            </div>



            <button type=" submit" class="btn btn-green text-white ">Tambah lokasi</button>
        </form>
    </div>
</div>
@stop
