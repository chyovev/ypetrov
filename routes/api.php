<?php

use App\API\Http\Controllers\CommentController;
use App\API\Http\Controllers\ContactController;
use App\API\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api')->name('api.')->group(function(): void {
    Route::post('/contact',       [ContactController::class, 'create_contact_message'])->name('contact');
    Route::post('/comments/{id}', [CommentController::class, 'create'])->name('comment');
    Route::post('/likes/{id}',    [LikeController::class, 'like'])->name('like');
    Route::delete('/likes/{id}',  [LikeController::class, 'revoke_like'])->name('revoke_like');
});