<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Mail\AccountApproved;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Redirect;
use URL;

// Admin 1
// graduates 2
// companies 3



// status
// 0--- pending approval
// 1--- Active
// 2--- Blocked

class MainController extends Controller
{



    public function graduates(Request $request)
    {
        return view('admin.graduates');
    }


    public function graduates_list(Request $request)
    {
        $graduates = User::where('role', 'graduate')->orderBy('id', 'DESC')->get();
        return $graduates;
    }

    public function update_status(Request $request)
    {
        $user = User::find($request->id);
        if ($request->status === 1 && $user->status  === 0) {
            $notification = new Notification;
            $notification->from = 1;
            $notification->to = $user->id;
            $notification->content = 'Congratulations your profile has been approved by Admin';
            $notification->save();

            // $server_key = env('SERVER_KEY');
            // $response = Http::withHeaders([
            //     'Content-Type' => 'application/json',
            //     'Authorization' => $server_key
            // ])->post('https://fcm.googleapis.com/fcm/send', [
            //     'to'            => '/topics/vehicles',
            //     'priority'      => 'high',
            //     'notification'  => [
            //        'title'      => "Your profile Approved",
            //        'body'       => 'Congratulations your profile has been approved by Admin'
            //     ]
            // ]);

        }
        $user->status = $request->status;
        $user->save();
        $details = [
            'title'   => 'Account Approved',
            'name'    => 'Admin',
            'email'   => 'contact@jobstarterapp.com',
            'graduate'=> $user->name
        ];
        \Mail::to($user->email)->send(new AccountApproved($details));
    }

    public function del_graduates($id)
    {
        $graduate = User::find($id)->delete();
        return Redirect::back()->with('msg', 'Password updated Successfully');
    }

    public function companies(Request $request)
    {
        return view('admin.companies');
    }

    public function companies_list(Request $request)
    {
        $companies = User::where('role', 'company')->orderBy('id', 'DESC')->get();
        return $companies;
    }
}
