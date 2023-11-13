<?php

use App\Admin\Http\Controllers\AttachmentController;
use App\Admin\Http\Controllers\AuthController;
use App\Admin\Http\Controllers\BookController;
use App\Admin\Http\Controllers\CommentController;
use App\Admin\Http\Controllers\ContactMessageController;
use App\Admin\Http\Controllers\GalleryImageController;
use App\Admin\Http\Controllers\EssayController;
use App\Admin\Http\Controllers\PoemController;
use App\Admin\Http\Controllers\PressArticleController;
use App\Admin\Http\Controllers\UserController;
use App\Admin\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

// routes which require no user to be logged in
Route::middleware(['guest:admin'])->group(function() {
    Route::view('/login', 'admin.auth.login')->name('admin.login');
    Route::post('/login',  [AuthController::class, 'process_login_request']);

    Route::view('/forgot-password', 'admin.auth.forgot_password')->name('admin.forgot_password');
    Route::post('/forgot-password', [AuthController::class, 'process_forgot_password_request']);
});

// routes which require a logged in user
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::view('/',      'admin.dashboard')->name('admin.home');

    // resources are a shortcut for all CRUD routes
    Route::name('admin')->resource('users',            UserController::class)->except(['show']);
    Route::name('admin')->resource('contact_messages', ContactMessageController::class)->only(['index', 'show', 'destroy']);
    Route::name('admin')->resource('poems',            PoemController::class)->except(['show']);
    Route::name('admin')->resource('books',            BookController::class)->except(['show']);
    Route::name('admin')->resource('essays',           EssayController::class)->except(['show']);
    Route::name('admin')->resource('press_articles',   PressArticleController::class)->except(['show']);
    Route::name('admin')->resource('videos',           VideoController::class)->except(['show']);
    Route::name('admin')->resource('gallery_images',   GalleryImageController::class)->except(['show']);
    Route::name('admin')->resource('attachments',      AttachmentController::class)->only('destroy');
    Route::name('admin')->resource('comments',         CommentController::class)->only('destroy');
});