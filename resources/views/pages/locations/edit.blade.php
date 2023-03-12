@extends('layouts.default')
@section('title','Update Location')

@section('content')
<div class="content bg-white p-2 px-4">
    <div class=" mt-5">
        <form enctype="multipart/form-data" action="{{route('locations.update',$location)}}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group ">
                <label for="exampleInputEmail1">Nama kota</label>
                <input type="text" class="form-control" name="city"  value="{{$location->city}}" placeholder="Nama Kota">
            </div>
             <div class="form-group  mt-3 mb-5 ">
                <label for="exampleInputEmail1">Provinsi</label>
                <input type="text" class="form-control" name="province"  value="{{$location->province}}" placeholder="Nama Provinsi">
            </div>



            <button type=" submit" class="btn btn-green text-white ">Update lokasi</button>
        </form>
    </div>
</div>
@stop
