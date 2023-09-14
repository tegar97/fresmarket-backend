@extends('layouts.default')

@section('title', 'List Product Freshmarket')

@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif

    <div class="content">
        <div class="mt-5">


            <button type="button" class="btn btn-primary-green mb-3" data-bs-toggle="modal" data-bs-target="#discountModal">
                Add Discount
            </button>
            </div>


            <table id="table" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>photo</th>
                        <th>Nama</th>
                        <th>Harga diskon</th>
                        <th>Besaran diskon</th>
                        <th>Lokasi tersedia</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productDiscount as $product)
                        <tr>
                            <td><img src="{{env('OSS_DOMAIN_PUBLIC')}}/icon/{{ $product->image }}" alt="Thumbnail " width="70" height="70" />
                            <td>{{ $product->name }}</td>

                            <td class="d-flex flex-column">
                                <del>
                                    {{ $product->price  }}

                                </del>
                                <span >
                                    {{ $product->price - ($product->price * $product->discount->discount_percetage / 100); }}
                                </span>


                            </td>
                            <td class="text-danger">{{$product->discount->discount_percetage }}%</td>

                            <td>{{ $product->locations()->pluck('city')->implode(', ') }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="discountModal" tabindex="-1" aria-labelledby="discountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
                @if (session()->has('errors'))
        <div class="alert alert-danger">
            {{ session()->get('errors') }}
        </div>
    @endif
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="discountModalLabel">Add Discount</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        @csrf
                            {{-- <div class="mb-3">
                                <label for="discount_name" class="form-label">Discount Name</label>
                                <input type="text" class="form-control" id="discount_name" name="discount_name" required>
                            </div>
                                <div class="mb-3">
                                <label for="discount_name" class="form-label"> Discount Explanation</label>
                                <input type="text" class="form-control" id="description" name="description" required>
                            </div>
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                            </div> --}}
                        <div class="mb-3">
                            <label for="discount_percentage" class="form-label">Discount Percentage</label>
                            <input type="number" class="form-control" id="discount_percentage" name="discount_percentage"
                                required min="1" max="99">
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
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>



@stop
