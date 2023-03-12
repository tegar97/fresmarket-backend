<?php
 Request::segment(2)
?>
@extends('layouts.default')
@section('title','List location')
@section('content')
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
    <div class="content">
        <div class=" mt-5">
            <a href={{route('recipes.create')}} class="btn btn-primary-green mb-3 ">Tambah Lokasi</a>
            <table id="table" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Nama Resep</th>
                        <th>Action</th>


                    </tr>
                </thead>
                <tbody>

                    @foreach ($recipes as $recipe)
                        <tr>


                            <td>{{ $recipe->province }}</td>
                            <td class="d-flex gap-2">
                                <a href="{{route('recipe.edit',$recipe)}}" class="btn btn-green text-white">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{route('recipe.destroy',$recipe)}}" method="POST">
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
