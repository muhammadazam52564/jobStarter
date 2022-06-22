@extends('layouts.admin.app')
@section('title')
    Create Category
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="row bg-white mt-4 shadow p-3 mb-5 bg-white rounded">
                <div class="col-md-12  py-2 pb-3 d-flex justify-content-between">
                    <h3>Edit New Category</h3>
                </div>
                <div class="col-md-12 ">
                    <form class="form-group pt-3" method="POST" action="{{ route('admin.update-category', $category->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="w-100">
                            <label >Category Name</label>
                            <input class="form-control" name="name" placeholder="category name" value="{{ $category->name }}"/>
                            @error('name')
                            <div class="text-danger"> * {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="w-100 d-flex py-2">
                            <div class="w-50 pr-2 pt-5">
                                <div class="w-100 d-flex justify-content-center">
                                    <label for="image" class="btn btn-lg btn-primary">Category Image</label>
                                    <input type="file" id="image" name="image" class="d-none"  onchange="previewImage(event, '#image_preview')"/>
                                    @error('image')
                                    <div class="text-danger"> * {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="w-50 pl-2 d-flex pt-2 justify-content-center">
                                <img src="../../{{ $category->image }}" style="max-width: 160px"  class="" id="image_preview"/>
                            </div>
                        </div>
                        <div class="pt-5 w-100 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Update Subscription</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        function previewImage(event, id) {
            imgInp = event.target;
            const [file] = imgInp.files
            $(id).removeClass('d-none')
            $(id).attr("src", URL.createObjectURL(file));
        }
    </script>
@endpush