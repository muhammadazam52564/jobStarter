<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Auth;
use URL;
use Carbon\Carbon;
use App\Models\User;
use Hash;
use Redirect;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function change_password(Request $request)
    {
        return view('admin.changePassword');
    }

    public function update_password(Request $request)
    {
        if (Hash::check($request->curr__password, Auth::user()->password)) {
            $user = User::find(Auth::user()->id);
            $user->password = bcrypt($request->new__password);
            if($user->save())
            {
                return Redirect::back()->with('msg', 'Password updated Successfully');
            }


        }
    }
    public function change_email(Request $request)
    {
        return view('admin.changeEmail');
    }

    public function update_email(Request $request)
    {
        if (Hash::check($request->password, Auth::user()->password)) {
            $user = User::find(Auth::user()->id);
            $user->email = $request->email;
            if($user->save())
            {
                return Redirect::back()->with('msg', 'Password updated Successfully');
            }


        }
    }



}
