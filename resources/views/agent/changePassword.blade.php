@extends('layouts.agent.app')
@section('content')
<div class="container">
    <div class="row">
    <div class="col-md-12 py-2 pb-3 d-flex justify-content-between">
        <h3>Change Admin Password</h3>
    </div>
        <div class="col-md-12 overflow-auto">
            <form class="form-group pt-3" method="POST" action="{{ route('agent.change-password') }}">
                @csrf
                <label class="mt-3"> Current Password </label>
                <input type="password" name="curr__password" class="form-control" placeholder="Current password" />
                <label class="mt-3"> New Password </label>
                <input type="password" name="new__password" class="form-control" placeholder="Nwe password" />
                <label class="mt-3"> Confirm New Password </label>
                <input type="password" name="confirm__new__password" class="form-control" placeholder="Confirm new password" />
                <div class="mt-4  w-100 d-flex justify-content-end">

                    <button class="btn btn-primary ">Set New Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
