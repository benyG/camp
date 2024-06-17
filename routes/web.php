<?php

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
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/boss/customer', [\App\Http\Controllers\StripeController::class, 'handleWebhook']);

Route::get('/ui2', function () {
    return view('welcome2');
})->name('home2');
Route::get('/ui3', function () {
    return view('welcome3');
})->name('home3');

Route::post('login', 'App\Filament\Pages\Auth\LoginController@authenticate2');
// OAuth2 routes
Route::post('auth/{driver}', 'App\Http\Controllers\SSOController@redirectToProvider')->name('oauth.redirect');
Route::get('auth/{driver}/callback', 'App\Http\Controllers\SSOController@handleProviderCallback')->name('oauth.callback');
Route::post('lang', 'App\Http\Controllers\LanguageController@langS')->name('lang');
Route::get('lang/{lg}', 'App\Http\Controllers\LanguageController@langS2')->name('lang2');

//Route::post('boss/{opt}', 'App\Filament\Pages\Auth\Login@authenticate2')->name('login2');
