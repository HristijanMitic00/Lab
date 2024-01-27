<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes();

# Home page
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/posts',[PostController::class,'index'])->name('home');

Route::group(['middleware' => ['auth']], function()
{
    Route::get('new-post',[PostController::class,'create']);

    Route::post('new-post',[PostController::class,'store']);

    Route::get('edit/{slug}',[PostController::class,'edit']);

    Route::post('update',[PostController::class,'update']);

    Route::post('comment/add',[CommentController::class,'store']);
});


Route::get('/logout', [LoginController::class,'logout']);
