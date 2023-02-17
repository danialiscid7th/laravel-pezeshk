<?php

use App\Http\Controllers\OtpController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(UserController::class)->group(function () {
    Route::any('/list', 'index');
    Route::any('/login', 'login');
    Route::any('/logout', 'logout');
    Route::any('/register', 'register');
    Route::any('/checkme', 'checkme');
    Route::any('/auth_check', 'auth_check');
    Route::get('/list-auth', 'index_auth')->middleware('auth');
});
Route::controller(OtpController::class)->group(function () {
    Route::any('/set-otp', 'setOtp');
    Route::any('/get-otp', 'getOtp');
    Route::any('/check-otp', 'checkOtp');
});
