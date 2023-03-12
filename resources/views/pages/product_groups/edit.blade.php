@extends('layouts.default')
@section('title', 'Update Product Group')

@section('content')
    @if(session()->has('errors'))
    <div class="alert alert-danger   ">
        {{ session()->get('errors') }}
    </div>
@endif
    <div class="content bg-white p-2 px-4">
        <div class=" mt-5">
          <form action="{{ route('product-groups.update',$productGroup) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text"      value="{{ old('title', $productGroup->title) }}"  class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $productGroup->description) }}</textarea>
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
                                                <input

                                                    @if($productGroup->products->contains($product->id))
                                                        checked
                                                    @endif

                                                type="checkbox" id="product_{{ $product->id }}" name="products[]"
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
                            <button type="submit" class="btn btn-primary-green">Save</button>
                        </div>
                    </form>
        </div>
    </div>
@stop
