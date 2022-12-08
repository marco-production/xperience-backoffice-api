<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GeolocationController;
use App\Http\Controllers\Api\WeakController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\AuthUserController;
use App\Http\Controllers\Api\Eticket\TravelerController;
use App\Http\Controllers\Api\Eticket\EticketController;

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

Route::group(['middleware' => ['cors', 'json.response']], function () {
    
    //Auth routes without login
    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/forgot-password', 'forgotPassword');
        Route::post('/forgot-password-code', 'validateForgotPasswordCode' );
        Route::post('/restore-password', 'restorePassword');

        //Account verification routes
        Route::post('/email-verification', 'emailVerification');
        Route::post('/phone-number-verification', 'phoneNumberVerification');
        Route::post('/code-verification', 'codeVerification');
    });

    Route::post('/enable-auth', [AuthUserController::class, 'enableAccount']);

    //Auth routes
    Route::middleware(['auth:api'])->group(function () {
        Route::controller(AuthUserController::class)->group(function () {
            Route::get('/auth-user', 'authUser');
            Route::post('/auth-user', 'update');
            Route::post('/disable-auth', 'disableAccount');
            Route::delete('/delete-auth', 'deleteAccount');
            Route::post('/reset-password', 'resetPassword');
            Route::post('/update-email', 'updateEmail');
            Route::post('/update-phone-number', 'updatePhoneNumber');
            Route::post('/auth-email-code-verification', 'emailCodeVerification');
            Route::post('/auth-phone-number-code-verification', 'phoneNumberCodeVerification');
        });

        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-everything', [AuthController::class, 'revokeAllTokens']);

        //API resource routes
        Route::apiResources([
            'roles'     => PermissionController::class,
            'travelers' => TravelerController::class,
            'etickets'  => EticketController::class,
        ]);

        //Geolocation routes
        Route::controller(GeolocationController::class)->group(function () {
            Route::get('/country/{id?}', 'getCountry');
            Route::get('/city/{iso?}', 'getCitiesByIso');
        });

        //Weak routes
        Route::controller(WeakController::class)->group(function () {
            Route::get('/port/{id?}', 'getPort');
            Route::get('/airline/{id?}', 'getAirline');
            Route::get('/hotel/{id?}', 'getHotel');
        });
    });

});