@extends('layouts.default')
@section('title', 'Edit Product')

@section('content')
    @if (session()->has('errors'))
        <div class="alert alert-danger">
            {{ session()->get('errors') }}
        </div>
    @endif
    <div class="content bg-white p-2 px-4">
        <div class=" mt-5">
            <form enctype="multipart/form-data" action="{{ route('products.update', $product->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                        value="{{ old('name', $product->name) }}" required autocomplete="name" autofocus>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>




                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" required
                        autocomplete="description">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" class="form-control @error('price') is-invalid @enderror" name="price"
                        value="{{ old('price', $product->price) }}" required autocomplete="price">
                    @error('price')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="weight">Weight</label>
                    <input type="number" class="form-control @error('weight') is-invalid @enderror" name="weight"
                        value="{{ old('weight', $product->weight) }}" required autocomplete="weight">
                    @error('weight')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="product_type">Product Type</label>
                    <input type="text" class="form-control @error('product_type') is-invalid @enderror"
                        name="product_type" value="{{ old('product_type', $product->product_type) }}" required
                        autocomplete="product_type">
                    @error('product_type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                  <div class="form-group">
                    <label for="product_calori">Product Calori</label>
                    <input type="number"   value="{{ old('product_type', $product->product_calori) }}"   class="form-control @error('product_calori') is-invalid @enderror"
                        name="product_calori" value="{{ old('product_calori') }}" required autocomplete="product_calori">
                    @error('product_calori')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" name="image"
                        value="{{ old('image', $product->image) }}" autocomplete="image">
                    @error('image')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    <img src="{{ asset('storage/' . $product->image) }}" alt="" width="100px">

                </div>



                <div class="form-group">
                    <label for="categories_id">Category</label>
                    <select name="categories_id" id="categories_id" class="form-control">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ $category->id == $product->category_id ? 'selected' : '' }}>
                                {{ $category->name }}</option>
                        @endforeach
                    </select>

                </div>


              <div class="form-group">
                    <label for="location">Location</label>
                    @foreach ($locations as $location)
                        <div class="form-check">
                            <input  @if (in_array($location->id, $product->locations->pluck('id')->toArray()))
                                checked

                            @endif  class="form-check-input" type="checkbox" name="locations[]" value="{{ $location->id }}"
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






                <button type="submit" class="btn btn-primary">Submit</button>

            </form>

        </div>

    </div>

@stop
