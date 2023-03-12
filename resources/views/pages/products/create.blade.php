@extends('layouts.default')
@section('title', 'Add Products')

@section('content')
    @if (session()->has('errors'))
        <div class="alert alert-danger">
            {{ session()->get('errors') }}
        </div>
    @endif
    <div class="content bg-white p-2 px-4">
        <div class=" mt-5">
            <form enctype="multipart/form-data" action="{{ route('products.store') }}" method="post">

                @csrf
                <div class="form-group ">
                    <label for="name">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                        value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>






                <div class="form-group mt-3">
                    <label for="description">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" required
                        autocomplete="description">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mt-3">
                    <label for="price">Harga</label>
                    <input type="number" class="form-control @error('price') is-invalid @enderror" name="price"
                        value="{{ old('price') }}" required autocomplete="price">
                    @error('price')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <div class="form-group mt-3">
                    <label for="weight">Weight</label>
                    <input type="number" class="form-control @error('weight') is-invalid @enderror" name="weight"
                        value="{{ old('weight') }}" required autocomplete="weight">
                    @error('weight')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mt-3">
                    <label for="product_type">Product Type</label>
                    <input type="text" class="form-control @error('product_type') is-invalid @enderror"
                        name="product_type" value="{{ old('product_type') }}" required autocomplete="product_type">
                    @error('product_type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mt-3">
                    <label for="product_calori">Product Calori</label>
                    <input type="number" class="form-control @error('product_calori') is-invalid @enderror"
                        name="product_calori" value="{{ old('product_calori') }}" required autocomplete="product_calori">
                    @error('product_calori')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mt-3">
                    <label for="categories_id">Kategori</label>
                    <select name="categories_id" class="form-control @error('categories_id') is-invalid @enderror" required>
                        <option value="" disabled selected>Pilih Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('categories_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('categories_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mt-3">
                    <label for="image">Image</label>
                    <input type="file" class="form-control-file @error('image') is-invalid @enderror" name="image"
                        required>
                    @error('image')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group mt-3 mb-3">
                    <label for="location">Location</label>
                    @foreach ($locations as $location)
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="locations[]" value="{{ $location->id }}"
                                id="location-{{ $location->id }}">
                            <label class="form-check-label" for="location-{{ $location->id }}">
                                {{ $location->city }}
                            </label>
                        </div>
                    @endforeach
                    @error('location')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>





                <button type=" submit" class="btn btn-green text-white ">Tambah Product</button>
            </form>
        </div>
    </div>
@stop
