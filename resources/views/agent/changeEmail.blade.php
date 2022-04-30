@extends('layouts.agent.app')
@section('content')
<div class="container">
    <div class="row">
    <div class="col-md-12 py-2 pb-3 d-flex justify-content-between">
        <h3>Change Admin Email Address</h3>
    </div>
        <div class="col-md-12 overflow-auto">
            <form class="form-group pt-3" method="POST" action="{{ route('agent.change-email') }}">
                @csrf
                <label class="mt-3"> Email Address </label>
                <input type="email" name="email" class="form-control" placeholder="email address" />
                <label class="mt-3"> Account Password </label>
                <input type="password" name="password" class="form-control" placeholder="Account password" />
                <div class="mt-4  w-100 d-flex justify-content-end">
                    <button class="btn btn-primary ">Change Email Address</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
