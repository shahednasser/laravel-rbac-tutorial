<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/post/{id?}', [PostController::class, 'postForm'])->name('post.form');

Route::post('/post/{id?}', [PostController::class, 'savePost'])->name('post.save');

Route::get('/post/access/{id}/{type}', [PostController::class, 'accessForm'])->name('post.access');

Route::post('/post/access/{id}/{type}', [PostController::class, 'saveAccess'])->name('post.access.save');