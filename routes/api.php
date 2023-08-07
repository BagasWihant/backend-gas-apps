<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\Login;
use App\Http\Controllers\Api\User\Profile;
use App\Http\Controllers\Api\Auth\Register;
use App\Http\Controllers\Api\Produk\ProdukFashionController;

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

// SIGN UP
Route::controller(Register::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/registerSendOtp', 'registerSendOtp')->middleware(['throttle:limit_2_per_menit']);
    Route::post('/registerConfirmOtp', 'registerConfirmOtp');


    // Route::get('/registerGoogle', 'registerGoogle');
    // Route::get('/handleLoginGoogle', 'handleLoginGoogle');
});
Route::controller(Login::class)->group(function () {
    Route::post('/login', 'login');

    Route::post('/resetPasswordSendOtp', 'resetPasswordSendOtp');
    Route::post('/resetPasswordConfirmOtp', 'resetPasswordConfirmOtp');
    Route::post('/resetPassword', 'resetPassword');

});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [Profile::class, 'index']);

    Route::resource('produk', ProdukFashionController::class);

});
