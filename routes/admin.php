<?php

use App\Admin\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// routes which require no user to be logged in
Route::middleware(['guest:admin'])->group(function() {
    Route::get('/login',   function() { return view('admin.login'); })->name('admin.login');
    Route::post('/login',  [AuthController::class, 'process_login_request']);
});

// routes which require a logged in user
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::get('/',       function() { return view('admin.dashboard'); })->name('admin.home');
});