<?php
 Request::segment(2)
?>
@extends('layouts.default')
@section('title','List  Outlet')
@section('content')
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
    <div class="content">
        <div class=" mt-5">
            <a href={{route('store.create')}} class="btn btn-primary-green mb-3 ">Tambah Outlet</a>
            <table id="table" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Nama Outlet</th>
                        <th>Kota</th>
                        <th>Alamat Lengkap</th>
                        <th>Action</th>


                    </tr>
                </thead>
                <tbody>

                    @foreach ($stores as $store)
                        <tr>


                            <td>{{ $store->name }}</td>
                            <td>{{ $store->location->city }}</td>
                            <td>{{ $store->address }}</td>


                            <td class="d-flex gap-2">
                                <a href="{{route('store.edit',$store)}}" class="btn btn-green text-white">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{route('store.destroy',$store)}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>

                                </form>
        </div>
        </td>

        </tr>
                            @endforeach

    </div>
    </tbody>


    </table>
    </div>
    </div>
@stop
