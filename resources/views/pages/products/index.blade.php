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

            <div class="d-flex  gap-3">
                <a href={{ route('products.create') }} class="btn btn-primary-green mb-3">Tambah Product</a>
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#discountModal">
                    Add Discount
                </button>

            </div>

            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="locationDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Pilih Lokasi
                </button>
                <ul class="dropdown-menu mb-3" aria-labelledby="locationDropdown">
                    @foreach ($locations as $location)
                        <li><a class="dropdown-item"
                                href="{{ route('products.index', ['location_id' => $location->id]) }}">{{ $location->city }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <table id="table" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>photo</th>
                        <th>Nama</th>
                        <th>Harga </th>
                        <th>Lokasi tersedia</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td><img src="{{env('OSS_DOMAIN_PUBLIC')}}/images/{{ $product->image }}" alt="Thumbnail " width="70" height="70" />
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->price }}</td>

                            <td>{{ $product->locations()->pluck('city')->implode(', ') }}</td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-green text-white">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
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
                        <form action={{route('discounts.store')}} method="post">
                            @csrf

                            <div class="mb-3">
                                <label for="discount_percentage" class="form-label">Discount Percentage</label>
                                <input type="number" class="form-control" id="discount_percentage"
                                    name="discount_percentage" required min="1" max="99">
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
                                                    <input type="checkbox" id="product_{{ $product->id }}"
                                                        name="products[]" value="{{ $product->id }}">
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
