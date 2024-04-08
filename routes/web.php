<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\EssayController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PressController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\SearchController;
use App\API\Http\Controllers\SwaggerController;
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

Route::get('/',                                  [StaticPageController::class, 'home'])->name('home');
Route::get('/hristomatiya',                      [StaticPageController::class, 'chrestomathy'])->name('chrestomathy');
Route::get('/za-yosif-petrov/{slug}',            [EssayController::class, 'view'])->name('essay');
Route::get('/galeriya',                          [GalleryController::class, 'index'])->name('gallery');
Route::get('/presa/{slug}',                      [PressController::class, 'view'])->name('press');
Route::get('/video/{slug}',                      [VideoController::class, 'view'])->name('video');
Route::get('/kontakt',                           [ContactController::class, 'index'])->name('contact');
Route::get('/tvorchestvo/{bookSlug}',            [WorkController::class, 'get_book'])->name('book');
Route::get('/tvorchestvo/{bookSlug}/{poemSlug}', [WorkController::class, 'get_poem'])->name('poem');
Route::get('/tarsene',                           [SearchController::class, 'index'])->name('search');

// make API swagger be accessible only for users logged in the CMS
Route::group(['middleware' => 'auth:admin'], function() {
    Route::get('/swagger',              [SwaggerController::class, 'swagger_interface']);
    Route::get('/swagger/openapi.yaml', [SwaggerController::class, 'swagger_docs'])->name('swagger_docs');
});
