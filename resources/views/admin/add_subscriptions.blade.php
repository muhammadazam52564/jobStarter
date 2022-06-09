@extends('layouts.admin.app')
@section('title')
    Create Subscription
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="row bg-white mt-4 shadow p-3 mb-5 bg-white rounded">
                <div class="col-md-12  py-2 pb-3 d-flex justify-content-between">
                    <h3>Add New Subscription</h3>
                </div>
                <div class="col-md-12 ">
                    <form class="form-group pt-3" method="POST" action="{{ route('admin.new-subscription') }}">
                        @csrf
                        <div class="w-100">
                            <label >Subscription Name</label>
                            <input class="form-control" name="name" placeholder="Subscription name" />
                            @error('name')
                            <div class="text-danger"> * {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="w-100 d-flex py-2">
                            <div class="w-50 pr-2">
                                <label >Subscription Duration</label>
                                <input type="number" name="duration" class="form-control" placeholder="duration" />
                                @error('duration')
                                <div class="text-danger"> * {{ $message }}</div>
                                @enderror
                            </div>
                            <div class="w-50 pl-2">
                                <label >Duration type</label>
                                <select name="type" class="form-control">
                                    <option value="days">Days</option>
                                    <option value="months">Months</option>
                                    <option value="years">Years</option>
                                </select>
                            </div>
                        </div>
                        <div class="w-100">
                            <label >Subscription Price</label>
                            <input name="amount" class="form-control" placeholder="Subscription price" />
                            @error('amount')
                                <div class="text-danger"> * {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="pt-3 w-100">
                            <button type="submit" class="btn btn-primary">Add Subscription</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection