<?php

use App\Admin\Http\Controllers\AuthController;
use App\Admin\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// routes which require no user to be logged in
Route::middleware(['guest:admin'])->group(function() {
    Route::view('/login', 'admin.login')->name('admin.login');
    Route::post('/login',  [AuthController::class, 'process_login_request']);
});

// routes which require a logged in user
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::view('/',      'admin.dashboard')->name('admin.home');

    // resources are a shortcut for all CRUD routes
    Route::name('admin')->resource('users', UserController::class)->except(['view']);
});