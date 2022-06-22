@extends('layouts.admin.app')
@section('title')
    Subscriptions
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-2 pb-3 pr-4 d-flex justify-content-between">
                <div class="">
                    <h3>Categories</h3>
                </div>
                <div>
                    <a href="{{ route('admin.add-category') }}" class="btn btn-success" >
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-12 overflow-auto shadow p-3 mb-5 bg-white rounded">
                <table class="table" style="min-width: 700px">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Category Nmae</th>
                            <th scope="col">Categories Image</th>
                            <th scope="col" style="min-width: 160px">Action</th>
                        </tr>
                    </thead>
                    <tbody id="companies_list">
                        @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>
                                <img src="../{{ $category->image }}" style="max-width: 100px" />
                            </td>
                            <td style="min-width: 160px" >
                                <a href="{{ route('admin.del-category', $category->id) }}" class="btn btn-sm btn-danger fa fa-trash mr-2"></button>
                                <a href="{{ route('admin.edit-category', $category->id) }}" class="btn btn-sm btn-primary fa fa-edit"></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('scripts')

@endpush
