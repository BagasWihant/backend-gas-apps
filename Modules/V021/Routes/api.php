<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\V021\Http\Controllers\Home\HomeController;
use Modules\V021\Http\Controllers\Auth\LoginController;
use Modules\V021\Http\Controllers\Store\StoreController;
use Modules\V021\Http\Controllers\User\AddressController;
use Modules\V021\Http\Controllers\User\ProfileController;
use Modules\V021\Http\Controllers\Auth\RegisterController;
use Modules\V021\Http\Controllers\Produk\ProdukController;
use Modules\V021\Http\Controllers\Explore\ExploreController;
use Modules\V021\Http\Controllers\Marketplace\KeranjangController;
use Modules\V021\Http\Controllers\Utility\GantiAkunController;

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


Route::prefix('v021')->group(function () {

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


    // EXPLORE cari barang
    Route::prefix('explore')->group(function (){
        Route::controller(ExploreController::class)->group(function(){
            Route::get('/find/{key}', 'temukanProduk');
            Route::get('/detail/{id}', 'detailProduk');
        });
    });

    // HARUS LOGIN DULU
    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/homepage', [HomeController::class, 'home']);

        // PROFILE
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'getData');
            Route::put('/profile', 'update');
            Route::post('/profile-foto', 'changeFoto');
        });

        // ADDRESS || KERANJANG
        Route::resources([
            'profile-address' => AddressController::class,
            'keranjang' => KeranjangController::class,
        ]);

        // REGISTER STORE
        Route::post('create-store', [StoreController::class, 'createStore']);

        // CREATE / SELL PRODUK
        Route::post('create-produk', [ProdukController::class, 'createProduk']);

        // GANTI AKUN
        Route::patch('user-to-store', [GantiAkunController::class, 'userToStore']);
        Route::patch('store-to-user', [GantiAkunController::class, 'storeToUser']);
    });
});
