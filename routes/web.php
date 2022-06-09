<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::group(['prefix'=> 'admin', 'middleware'=>['isAdmin', 'auth', 'PreventBackHistory']], function()
{
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/graduates', [MainController::class, 'graduates'])->name('admin.graduates');

    Route::get('/get-graduates', [MainController::class, 'graduates_list'])->name('admin.get-graduates');

    Route::get('/del-graduate/{id}', [MainController::class, 'del_graduates'])->name('admin.del-graduate');

    Route::get('/companies', [MainController::class, 'companies'])->name('admin.companies');

    Route::get('/get-companies', [MainController::class, 'companies_list'])->name('admin.get-companies');

    Route::post('/update-status', [MainController::class, 'update_status'])->name('admin.update-status');

    Route::get('/subscriptions',    [MainController::class, 'subscriptions'])->name('admin.subscriptions');
    Route::get('/add-subscription', [MainController::class, 'add_subscription'])->name('admin.add-subscription');
    Route::post('/new-subscription',    [MainController::class, 'new_subscription'])->name('admin.new-subscription');


    Route::get('/get-subscriptions', [MainController::class, 'subscriptions_list'])->name('admin.get-subscriptions');

    Route::get('/edit-subscription/{id}', [MainController::class, 'edit_subscriptions'])->name('admin.edit-subscription');
    Route::post('/update-subscription/{id}', [MainController::class, 'update_subscription'])->name('admin.update-subscription');

    Route::get('/del-subscription/{id}', [MainController::class, 'del_subscription'])->name('admin.del-subscription');

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
