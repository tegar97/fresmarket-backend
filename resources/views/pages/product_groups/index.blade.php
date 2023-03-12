<?php
Request::segment(2);
?>
@extends('layouts.default')
@section('title', 'Grouping Product ')
@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif



    <div class="content">
        <div class=" mt-5">
            <div class="d-flex  gap-3">
                <button type="button" class="btn btn-primary-green mb-3" data-bs-toggle="modal" data-bs-target="#productGroup">
                    Tambah Group Product
                </button>

            </div>
            <table id="table" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Total Product</th>
                        <th>Action</th>



                    </tr>
                </thead>
                <tbody>

                    @foreach ($productGroups as $group)
                        <tr>


                            <td>{{ $group->title }}</td>
                            <td>{{ $group->groupProducts->count() }}</td>



                            <td class="d-flex gap-2">
                                <a href="{{ route('product-groups.edit', $group) }}" class="btn btn-green text-white">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('product-groups.destroy', $group) }}" method="POST">
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
    <div class="modal fade" id="productGroup" tabindex="-1" aria-labelledby="productGroupLabel" aria-hidden="true">
        <div class="modal-dialog">
            @if (session()->has('errors'))
                <div class="alert alert-danger">
                    {{ session()->get('errors') }}
                </div>
            @endif
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productGroupLabel">Group Prouct</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('product-groups.store') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="product_list" class="form-label">Products</label>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>
                                                <input type="checkbox" id="product_{{ $product->id }}" name="products[]"
                                                    value="{{ $product->id }}">
                                            </td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->price }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary-green">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
