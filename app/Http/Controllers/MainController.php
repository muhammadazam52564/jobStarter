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
use App\Models\Subscription;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Redirect;
use Session;
use Stripe;
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

    public function subscriptions(Request $request)
    {
        return view('admin.subscriptions');
    }
    public function add_subscription(Request $request)
    {
        return view('admin.add_subscriptions');
    }
    public function new_subscription(Request $request)
    {
        try{
            $validated = $request->validate([
                'name'      => 'required',
                'amount'    => 'required',
                'duration'  => 'required',
                'type'      => 'required',
            ]);
            $subscription           = new Subscription;
            $subscription->name     =  $request->name;
            $subscription->amount   =  $request->amount;
            $subscription->duration =  $request->duration;
            $subscription->type     =  $request->type;
            if ($subscription->save()) 
            {
                return redirect()->route('admin.subscriptions');
            }
            else
            {
                return "failed to save";
            }
        }
        catch(\Exception $e){

            return response()->json([
                'status'    => false,
                'error'     => $e->getMessage(),
                'data'      => null
            ], 400);
        }
    }

    public function edit_subscriptions($id)
    {
        $subscription = Subscription::find($id);
        return view('admin.edit_subscription', compact('subscription'));
    }

    public function update_subscription($id, Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required',
            'amount'    => 'required',
            'duration'  => 'required',
            'type'      => 'required',
        ]);
        $subscription           =  Subscription::find($id);
        $subscription->name     =  $request->name;
        $subscription->amount   =  $request->amount;
        $subscription->duration =  $request->duration;
        $subscription->type     =  $request->type;
        if ($subscription->save()) 
        {
            return redirect()->route('admin.subscriptions');
        }
        else
        {
            return "failed to save";
        }
    }
    
    public function subscriptions_list(Request $request){
        $companies = Subscription::orderBy('id', 'DESC')->get();
        return $companies;
    }

    public function del_subscription($id){
        $subscription = Subscription::find($id)->delete();
        return Redirect::back()->with('msg', 'Subscription deleted Successfully');
    }


    public function categories(Request $request)
    {
        $categories = Category::orderBy('id', 'DESC')->get();
        return view('admin.categories', compact('categories'));
    }

    public function add_category(Request $request)
    {
        try{
            $validated = $request->validate([
                'name'    => 'required',
                'image'   => 'required',
            ]);
            $category           = new Category;
            $category->name     = $request->name;
            if ($request->has('image')) 
            {
                $newfilename        = time() .'.'. $request->image->getClientOriginalExtension();
                $request->file('image')->move(public_path("categories"), $newfilename);
                $category->image     = 'categories/'.$newfilename;
            }

            if ($category->save()) 
            {
                return redirect()->route('admin.categories');
            }
            else
            {
                return back();
            }
        }
        catch(\Exception $e){
            return back()->with('message',  $e->getMessage());
        }
    }
    public function edit_category($id)
    {
        $category = Category::find($id);
        return view('admin.edit_category', compact('category'));
    }

    public function update_category(Request $request, $id)
    {
        try{
            $validated = $request->validate([
                'name'    => 'required'
            ]);
            $category           = Category::find($id);
            $category->name     = $request->name;
            if ($request->has('image')) 
            {
                $newfilename        = time() .'.'. $request->image->getClientOriginalExtension();
                $request->file('image')->move(public_path("categories"), $newfilename);
                $category->image     = 'categories/'.$newfilename;
            }

            if ($category->save()) 
            {
                return redirect()->route('admin.categories');
            }
            else
            {
                return back();
            }
        }
        catch(\Exception $e){
            return back()->with('message',  $e->getMessage());
        }
    }

    public function del_category($id)
    {
        $category = Category::find($id)->delete();
        return back()->with('msg',  "Deleted Successfully");
    }
}
