<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Middleware\StdAuth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\StyleController;



Route::get('/', function () {
    return view('welcome');
});

/**
 * ########################################################################
 *           LOGIN/LOGOUT/SIGNIN
 * ########################################################################
 */

Route::get('/login', function () {
    return view('auth.login');
});

Route::post('/login', function (Request $request) {
    return AuthController::StdLogin($request);
});

Route::get('/logout', function (Request $request) {
    return AuthController::StdLogout($request);
});

Route::get('/signin', function(){
    return view('auth.signin');
});

Route::post('/signin', function (Request $request) {
    return AuthController::StdSignin($request);
});

Route::get('/forgot-password', function (Request $request) {
    return view('auth.forgot-password');
});

Route::post('/forgot-password', function (Request $request) {
    return AuthController::StdForgotPassword($request);
});

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
});

Route::post('/reset-password/{token}', function (Request $request) {
    return  AuthController::StdResetPassword($request);
});

/**
 * ########################################################################
 *           Account
 * ########################################################################
 */

Route::get('/profile', function () {
    return view('account.self', [
        'currentStyle' => StyleController::getCurrentStyle(Auth::user()->id),
        'styles' => StyleController::getStyles(Auth::user()->id),
        'stats' => ProfileController::getStats(Auth::user()->id),
        'ranks' => ProfileController::getRanks(Auth::user()->id),
        'history' => ProfileController::getSessionsHistory(Auth::user()->id)
    ]);
});

Route::get('/profile/{id}', function (String $id) {
    return view('welcome');
});

/**
 * ########################################################################
 *           DEBUG
 * ########################################################################
 */

Route::get('/debug/data-view', function () {
    return view('debug.data-view', ['data' => StyleController::getStyle(Auth::user()->id)]);
})->middleware([StdAuth::class]);
