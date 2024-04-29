<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MusicController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// auth
Route::prefix('/v1/')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot_password', [AuthController::class, 'forgot_password']);
    Route::post('reset_password', [AuthController::class, "reset_password"])->name("password.reset");


    Route::prefix('/music')->controller(MusicController::class)->group(function () {
        Route::get('/','index');
        Route::post('/', 'store');
    });

    Route::prefix('/artist')->controller(ArtistController::class)->group(function () {
        Route::get('/','index');
        Route::post('/', 'store');
    });

    Route::prefix('/album')->controller(AlbumController::class)->group(function(){
        Route::post('/','store');
    });
});
