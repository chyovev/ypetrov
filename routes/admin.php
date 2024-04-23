<?php

use App\Admin\Http\Controllers\AttachmentController;
use App\Admin\Http\Controllers\AuthController;
use App\Admin\Http\Controllers\BookController;
use App\Admin\Http\Controllers\CommentController;
use App\Admin\Http\Controllers\ContactMessageController;
use App\Admin\Http\Controllers\GalleryImageController;
use App\Admin\Http\Controllers\EssayController;
use App\Admin\Http\Controllers\DashboardController;
use App\Admin\Http\Controllers\PoemController;
use App\Admin\Http\Controllers\PressArticleController;
use App\Admin\Http\Controllers\StaticPageController;
use App\Admin\Http\Controllers\UserController;
use App\Admin\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

// routes which require no user to be logged in
Route::middleware(['guest:admin'])->group(function() {
    Route::view('/login', 'admin.auth.login')->name('admin.login');
    Route::post('/login',  [AuthController::class, 'process_login_request']);

    Route::view('/forgot-password', 'admin.auth.forgot_password')->name('admin.forgot_password');
    Route::post('/forgot-password', [AuthController::class, 'process_forgot_password_request']);

    // route name should be compatible with the ResetPassword::resetUrl() method
    Route::get('/reset-password',  [AuthController::class, 'show_reset_password_form'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'process_reset_password_request']);
});

// routes which require a logged in user
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::get('/',       [DashboardController::class, 'index'])->name('admin.home');

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
    Route::name('admin')->resource('static_pages',     StaticPageController::class)->only('edit', 'update');

    // the following tables can be reordered
    Route::reorder(['books', 'essays', 'press_articles', 'videos', 'gallery_images']);
});