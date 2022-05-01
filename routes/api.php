<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MainController;
use Illuminate\Support\Facades\Auth;



Route::post('/signup', [AuthController::class, 'signup']);

Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function(){

    Route::post('/update-profile', [AuthController::class, 'update_profile']);
    Route::post('/verify-otp', [AuthController::class, 'verify_otp']);
    Route::post('/update-profile-image', [AuthController::class, 'update_profile_image_by_parts']);
    Route::get('/profile/{id}', [AuthController::class, 'profile']);
    Route::get('/graduates/', [AuthController::class, 'graduates']);
    Route::post('/send_mail', [AuthController::class, 'send_mail']);
    Route::get('/logout/{id}', [AuthController::class, 'signout']);
    Route::get('/notifications/{id}', [AuthController::class, 'notifications']);
    Route::post('/pay', [AuthController::class, 'payment']);


});

// Route::post('/update_profile', [AuthController::class, 'update_profile']);
// Route::post('/change_password', [AuthController::class, 'change_password']);

