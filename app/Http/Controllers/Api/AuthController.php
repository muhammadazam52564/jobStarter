<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Notification;
use Illuminate\Support\Str;
use App\Mail\ShortListed;
use App\Models\Payment;
use App\Mail\OtpMail;
use App\Models\User;
use Carbon\Carbon;
use Stripe;

class AuthController extends Controller
{
    public function signup(Request $request){
        try
        {
            $validator = \Validator::make($request->all(), [
                'name'      => 'required',
                'email'     => 'required|unique:users',
                'password'  => 'required|min:6|max:30',
                'type'      => 'required'
            ]);
            if ($validator->fails()){
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors()->first(),
                    'data' => null
                ], 400);
            }else{
                $user           = new User;
                $user->name     = $request->name;
                $user->email    = $request->email;
                $user->role     = $request->type;
                if ($request->type == 'company') {
                    $user->status = 1;
                    $user->otp      = null;
                }else{
                    $user->otp      = 1234;
                }
                if( $request->has('token')){
                    $user->token = $request->token;
                    $user->save();
                }

                $details = [
                    'title' => 'Email Account Verification',
                    'body'  => '',
                    'code'  => $user->otp,
                    'name'  => $request->name
                ];
                // $user->otp   = ;
                $user->password = bcrypt($request->password);
                if ( $user->save()) {
                    \Mail::to($request->email)->send(new OtpMail($details));
                    $token = $user->createToken('my-app-token')->plainTextToken;
                    return response()->json([
                        'status'    => true,
                        'message'   => 'SignUp Successfully ',
                        'token'     => $token,
                        'data'      =>  $user->makeHidden(['verified_at', 'updated_at', 'created_at']),
                    ]  , 200);
                }
            }
        }catch(\Exception $e){

            return response()->json([
                'status'    => false,
                'error'     => $e->getMessage(),
                'data'      => null
            ], 400);
        }
    }

    public function verify_otp(Request $request){
        try{
            $validator = \Validator::make($request->all(), [
                'user_id'    => 'required',
                'otp'        => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors()->first(),
                ], 400);
            }
            $user = User::find($request->user_id);
            if($request->otp == $user->otp)
            {
                $user->email_verified_at = Carbon::now();
                $user->otp = null;
                $user->verified = 1;
                $user->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Account Successfully Verified!',
                    'data' => $user->makeHidden([ "email_verified_at", "role", "status", "otp", "created_at",  "updated_at" ]),
                ], 200);
            }else{
                return response()->json([
                    'status' => 400,
                    'message' => 'Invalid Code!',
                    'data' => null,
                ], 200);
            }
        }catch(\Exception $e)
        {
            if($request->expectsJson())
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'There is some trouble to proceed your action!',
                    'data' => $e->getMessage(),
                ], 200);
            }
        }
    }

    public function login(Request $request){
        try
        {
            $validator = \Validator::make($request->all(), [
                'email'     => 'required',
                'password'  => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'error'     => $validator->errors()->first(),
                    'data'      => null
                ], 400);
            }else{
                if (auth()->attempt(['email' => $request->email, 'password' => $request->password]))
                {
                    $user = auth()->user();
                    $token = $user->createToken('my-app-token')->plainTextToken;
                    if( $request->has('token')){
                        $user->token = $request->token;
                        $user->save();
                    }

                    return response()->json([
                        'status'    => true,
                        'message'   => 'Successfully Loged In',
                        'token'     => $token,
                        'data'      => auth()->user()->makeHidden(['created_at', 'updated_at', 'otp', 'email_verified_at']),
                    ], 200);
                }else{
                    return response()->json([
                        'status'    => true,
                        'message'   => 'Invalid Credentials',
                        'data'      => null,
                    ], 200);
                }

            }
        } catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function update_profile_image_by_parts(Request $request){
        try{
            $validator = \Validator::make($request->all(), [
                'user_id'   => 'bail|required',
                'image'     => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'error'     => $validator->errors()->first(),
                    'data'      => null,
                ], 400);
            }
            else{
                $user = User::find($request->user_id);
                if(empty($user)){
                    return response()->json([
                        'status'    => false,
                        'message'   => 'User does not exists!',
                        'data'      => null,
                    ], 400);
                }else{
                    $newfilename          = time() .'.'. $request->image->getClientOriginalExtension();
                    $request->file('image')->move(public_path("profile_images"), $newfilename);
                    $user->profile_image  = 'profile_images/'.$newfilename;
                    if($user->save())
                    {
                        return response()->json([
                            'status'    => true,
                            'message'   => 'Profile Image Updated Successfully!',
                            'data'      => $user->makeHidden(['created_at', 'updated_at', 'email_verified_at', 'otp']),
                        ], 200);
                    }
                }
            }
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage(),
                'data' => 0,
            ], 200);
        }
    }

    public function update_profile(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'user_id'   => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'error'     => $validator->errors()->first(),
                    'data'      => null
                ], 400);
            }else{
                $user = User::find($request->user_id);
                if(empty($user)){
                    return response()->json([
                        'status'    => 400,
                        'error'     => 'User Does Not Exists!',
                        'data'      => null,
                    ], 400);
                }else{
                    if($request->has('name')){
                        $user->name = $request->name;
                    }
                    if($request->has('email')){

                        $user->email = $request->email;
                    }
                    if($request->has('dob')){
                        // date formate required 'm/d/Y', '2020-12-08'
                        $user->dob =  Carbon::createFromFormat('Y-d-m', $request->dob);
                    }
                    if($request->has('address')){
                        $user->address = $request->address;
                    }
                    if($request->has('school')){
                        $user->school = $request->school;
                    }
                    if($request->has('description')){
                        $user->description = $request->description;
                    }
                    if($user->save()){
                        $user = User::find($request->user_id);
                        return response()->json([
                            'status'    => 200,
                            'message'   => 'Profile Updated Successfully!',
                            'data'      => $user->makeHidden(['created_at', 'updated_at', 'email_verified_at', "role", "status", "verified", "otp"]),
                        ], 200);
                    }
                }
            }
        }catch(\Exception $e)
        {
            return response()->json([
                'status'    => 400,
                'error'     => $e->getMessage(),
                'data'      => null
            ], 400);
        }
    }

    public function change_password(Request $request){
        try{
             $validator = \Validator::make($request->all(), [
                'user_id'       => 'bail|required',
                'old_password'  => 'required',
                'password'      => 'required|min:6',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'error'     => $validator->errors()->first(),
                    'data'      => null
                ], 400);
            }
            $user = User::find($request->user_id);
            if(empty($user))
            {
                return response()->json([
                    'status'    => false,
                    'error'     => 'user not exist',
                    'data'      => null,
                ], 400);
            }
            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json([
                    'status'    =>  false,
                    'error'     => 'incorrect old paasord',
                    'data'      => null,
                ], 400);
            }
            $user->password = bcrypt($request->password);
            if($user->save()){
                return response()->json([
                    'status'    => true,
                    'message'   => 'Password Changed Successfully!',
                    'data'      => $user->makeHidden(['created_at', 'updated_at', 'verification_code', 'type', 'token']),
                ], 200);
            }
        }catch(\Exception $e)
        {
            return response()->json([
                'status'    => false,
                'error'     => $e->getMessage(),
                'data'      => null,
            ], 400);
        }
    }

    public function forgot_password(Request $request){
        try{
            $validator = \Validator::make($request->all(), [
                'email' => 'bail|required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'error'     => $validator->errors()->first(),
                    'data'      => null
                ], 400);
            }
            $user = User::where('email', $request->email)->first();
            if(empty($user))
            {
                return response()->json([
                    'status'    => false,
                    'error'     => 'User does not exists!',
                    'data'      => null,
                ], 200);
            }
            // $code = rand(1000, 9999);
            $code = 1234;
            $user->otp = $code;
            $user->save();
            $data = [
                "opt"=> $code,
            ];
            // \Mail::to($request->email)->send(new ForgotPassword($code));
                return response()->json([
                    'status'    => true,
                    'message'   => 'A Verification Code has been Sent to your Email!',
                    'data'      => $data,
                ], 200);
        }catch(\Exception $e)
        {
            return response()->json([
                'status'    => false,
                'message'   => 'There is some trouble to proceed your action!',
                'data'      => null,
            ], 200);
        }
    }

    public function set_password(Request $request){
        try{
            $validator = \Validator::make($request->all(), [
                'email' => 'bail|required',
                'password' => 'required|min:6',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'error'     => $validator->errors()->first(),
                    'data'      => null
                ], 400);
            }
            $user = User::where('email', $request->email)->first();
            if(empty($user))
            {
                return response()->json([
                    'status'    => false,
                    'message'   => 'User does not exists!',
                    'data'      => null,
                ], 200);
            }
            $user->password = bcrypt($request->password);
            if($user->save()){
                return response()->json([
                    'status'    => true,
                    'message'   => 'Password Changed Successfully!',
                    'data'      => $user->makeHidden(['created_at', 'updated_at', 'email_verified_at', 'verification_code', 'cover_image', 'self_description', 'opening_time', 'type']),
                ], 200);
            }
        }catch(\Exception $e)
        {
            if($request->expectsJson)
            {
                return response()->json([
                    'status' => 400,
                    'error' => $e->getMessage(),
                    'data'  => null
                ], 400);
            }
        }
    }

    public function profile($id){
        try{
            $user = User::find($id);
            return response()->json([
                'status'    => true,
                'message'   => "User Information",
                'data'      => $user->makeHidden(["role", "status", "verified", "otp", "created_at", "updated_at", 'email_verified_at' ])
            ], 400);

        }catch(\Exception $e)
        {
            if($request->expectsJson)
            {
                return response()->json([
                    'status'    => false,
                    'error'     => $e->getMessage(),
                    'data'      => null
                ], 400);
            }
        }
    }

    public function graduates(Request $request){
        try{
            $user = User::where('role', 'graduate')->select("id", "name", "email", "profile_image", "school", "address", "description", "dob")->get();

            return response()->json([
                'status'    => true,
                'message'     => "Graduates List",
                'data'      => $user
            ], 200);

        }catch(\Exception $e)
        {
            if($request->expectsJson)
            {
                return response()->json([
                    'status' => false,
                    'error' => $e->getMessage(),
                    'data'  => null
                ], 400);
            }
        }
    }

    public function send_mail(Request $request){
        try{
             $validator = \Validator::make($request->all(), [
                'company_id'       => 'required',
                'graduate_id'      => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'error'     => $validator->errors()->first(),
                    'data'      => null
                ], 400);
            }
            $company  = User::find($request->company_id);
            $graduate = User::find($request->graduate_id);
            $notification = new Notification;
            $notification->from = $company->id;
            $notification->to = $graduate->id;
            $notification->content = 'Congratulations you are shot listed by '. $company->name;
            $notification->save();

            $server_key = env('SERVER_KEY');
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $server_key
            ])->post('https://fcm.googleapis.com/fcm/send', [
                'to'            => $graduate->token,
                'priority'      => 'high',
                'notification'  => [
                   'title'      => "You are shot listed",
                   'body'       => 'Congratulations you are shot listed by '. $company->name
                ]
            ]);

            $details = [
                'title'      => 'Short Listed Graduate Mail',
                'company'    => $company,
                'graduate'   => $graduate
            ];
            \Mail::to($graduate->email)->send(new ShortListed($details));
            return response()->json([
                'status' => true,
                'message' => 'Email Successfully Send',
                'data' => null,
            ], 200);
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function notifications($id, Request $request){
        try{

            $notifications = Notification::where('to', $id)->select('id', 'content', 'created_at')->get();
            return response()->json([
                'status' => true,
                'message' => 'Notifications !',
                'data' => $notifications,
            ], 200);
        }
        catch(\Exception $e)
        {
            if($request->expectsJson())
            {
                return response()->json([
                    'status' => false,
                    'message' => 'There is some trouble to proceed your action!',
                    'data' => null,
                ], 200);
            }
        }
    }

    public function signout($id, Request $request){
        try{
            $user = User::find($id);
            // return $user;
            if(empty($user))
            {
                return response()->json([
                    'status' => false,
                    'message' => 'User does not exists! ',
                    'data' => null,
                ], 200);
            }

            $user->token = null;
            if($user->save())
            {
                return response()->json([
                    'status' => true,
                    'message' => 'Logged Out Successfullty !',
                    'data' => null,
                ], 200);
            }
        }
        catch(\Exception $e)
        {
            if($request->expectsJson())
            {
                return response()->json([
                    'status' => false,
                    'message' => 'There is some trouble to proceed your action!',
                    'data' => null,
                ], 200);
            }
        }
    }

    public function subscriptions(Request $request)
    {
        $subscriptions = Subscription::orderBy('id', 'DESC')->get();
        return response()->json([
            'status'    => true,
            'message'   => 'subscriptions List',
            'data'      => $subscriptions
        ], 200);
    }
    public function payment(Request $request)
    {
        // return Payment::all();
        try
        {
            $validator = \Validator::make($request->all(), [

                'company'       => 'required',
                'card_number'   => 'required',
                'exp_month'     => 'required|min:1|max:2',
                'exp_year'      => 'required|min:4|max:4',
                'cvc'           => 'required|min:3|max:3',
                'amount'        => 'required',
                'currency'      => 'required'

            ]);
            if ($validator->fails()){
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors()->first(),
                    'data' => null
                ], 400);
            }else{

                Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

                $stripe = new \Stripe\StripeClient(
                    env('STRIPE_SECRET')
                );
                $token = $stripe->tokens->create([
                    'card' => [
                    'number'    => $request->card_number,
                    'exp_month' => $request->exp_month,
                    'exp_year'  => $request->exp_year,
                    'cvc'       => $request->cvc,
                    ],
                ]);

                $charge = Stripe\Charge::create ([
                        "amount"        => $request->amount *100,
                        "currency"      => $request->currency,
                        "source"        => $token->id,
                        "description"   => "Subsrciption test payment."
                ]);

                $res = [
                    'payment_id'    => $charge->id,
                    'status'        => $charge->status,
                    'amount'        => $charge->amount / 100 .' '. $charge->currency
                ];
                $payment = new Payment;
                $payment->company        = $request->company;
                $payment->transaction_id = $charge->id;
                $payment->description    = 'Subsrciption payment.';
                $payment->save();
                return response()->json([
                    'status'    => true,
                    'message'   => "Payment successfully completed",
                    'data'      => $res
                ], 200);
            }
        }catch(\Exception $e){

            return response()->json([
                'status'    => false,
                'error'     => $e->getMessage(),
                'data'      => null
            ], 400);
        }

    }

}

