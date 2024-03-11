<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

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

Route::post('login', 'App\Filament\Pages\Auth\LoginController@authenticate2');
// OAuth2 routes
Route::post('auth/{driver}', 'App\Http\Controllers\SSOController@redirectToProvider')->name('oauth.redirect');
Route::get('auth/{driver}/callback', 'App\Http\Controllers\SSOController@handleProviderCallback')->name('oauth.callback');
Route::post('lang', 'App\Http\Controllers\LanguageController@langS')->name('lang');

