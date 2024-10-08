<?php

use App\API\Http\Controllers\CommentController;
use App\API\Http\Controllers\ContactController;
use App\API\Http\Controllers\LikeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/contact',       [ContactController::class, 'create_contact_message'])->name('api.contact');
Route::post('/comments/{id}', [CommentController::class, 'create'])->name('api.comment');
Route::post('/likes/{id}',    [LikeController::class, 'like'])->name('api.like');
Route::delete('/likes/{id}',  [LikeController::class, 'revoke_like'])->name('api.revoke_like');