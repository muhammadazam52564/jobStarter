<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');



Route::group(['prefix'=> 'admin', 'middleware'=>['isAdmin', 'auth', 'PreventBackHistory']], function()
{
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/graduates', [MainController::class, 'graduates'])->name('admin.graduates');

    Route::get('/get-graduates', [MainController::class, 'graduates_list'])->name('admin.get-graduates');


    Route::get('/del-graduate/{id}', [MainController::class, 'del_graduates'])->name('admin.del-graduate');


    Route::get('/companies', [MainController::class, 'companies'])->name('admin.companies');

    Route::get('/get-companies', [MainController::class, 'companies_list'])->name('admin.get-companies');

    Route::post('/update-status', [MainController::class, 'update_status'])->name('admin.update-status');


    //
    // Profile Settings
    //
    Route::get('/change-password', [AdminController::class, 'change_password'])->name('admin.change-password');
    Route::post('/change-password', [AdminController::class, 'update_password'])->name('admin.change-password');
    Route::get('/change-email', [AdminController::class, 'change_email'])->name('admin.change-email');
    Route::post('/change-email', [AdminController::class, 'update_email'])->name('admin.change-email');


});










Route::group(['prefix'=> 'agent', 'middleware'=>['isAgent', 'auth', 'PreventBackHistory']], function()
{
    Route::get('/dashboard', [AgentController::class, 'index'])->name('agent.dashboard');
});

Route::group(['prefix'=> 'user', 'middleware'=>['isUser', 'auth', 'PreventBackHistory']], function()
{

    Route::get('dashboard', [UserController::class, 'index'])->name('user.dashboard');

});

Route::group(['prefix'=> 'rider', 'middleware'=>['isRider', 'auth', 'PreventBackHistory']], function()
{
    Route::get('dashboard', [RiderController::class, 'index'])->name('rider.dashboard');
});
