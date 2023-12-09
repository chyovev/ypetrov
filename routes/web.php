<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\WorkController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/tvorchestvo/{bookSlug}',            [WorkController::class, 'get_book'])->name('book');
Route::get('/tvorchestvo/{bookSlug}/{poemSlug}', [WorkController::class, 'get_poem'])->name('poem');
Route::get('/galeriya',                          [GalleryController::class, 'index'])->name('gallery');
