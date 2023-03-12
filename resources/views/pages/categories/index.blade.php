@extends('layouts.default')
@section('title', 'Data Category')

@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    <div class="content">
        <div class=" mt-5">
            <a href={{ route('categories.create') }} class="btn btn-primary-green mb-3 ">Tambah Kategori</a>
            <h1></h1>
            <table id="table" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>icon</th>
                        <th>nama</th>
                        <th>description</th>
                        <th>color</th>
                        <th>action</th>

                    </tr>
                </thead>
                <tbody>

                    @foreach ($categories as $category)
                        <tr>
                            <td><img src="{{env('OSS_DOMAIN_PUBLIC')}}/icon/{{ $category->icon }}" alt="Thumbnail " width="70" height="70" />
                            </td>

                            <td>{{ $category->name }}</td>
                            <td>{{ $category->description }}</td>
                            <td>
                                <div class="color-demo" style="background-color: <?= $category->bgColor ?>"></div>
                            </td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-green text-white">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST">
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
