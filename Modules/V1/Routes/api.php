<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\V1\Http\Controllers\Auth\LoginController;
use Modules\V1\Http\Controllers\Auth\RegisterController;
use Modules\V1\Http\Controllers\Home\HomeController;
use Modules\V1\Http\Controllers\User\ProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {

    // register
    Route::controller(RegisterController::class)->group(function () {
        Route::post('/registerSendOtp', 'registerSendOtp');
        Route::post('/registerConfirmOtp', 'registerConfirmOtp');
        Route::post('/register', 'register');
    });


    // LOGIN
    Route::controller(LoginController::class)->group(function () {
        Route::post('/login', 'login');

        Route::post('/resetPasswordSendOtp', 'resetPasswordSendOtp');
        Route::post('/resetPasswordConfirmOtp', 'resetPasswordConfirmOtp');
        Route::post('/resetPassword', 'resetPassword');
    });

    // HARUS LOGIN DULU
    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/homepage', [HomeController::class, 'home']);

        // PROFILE
        Route::get('/profile', [ProfileController::class, 'getData']);
        Route::put('/profile', [ProfileController::class, 'update']);

    });


});
