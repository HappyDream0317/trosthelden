<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//Route everything except nova to vue app
Route::get('/{all}', 'WebController@index')->where('all', '^((?!nova|nova-impersonate).)*');

//Register Navigation routs afterwards, so they do not work but are known for Mail rendering
Auth::routes(['verify' => true]);
Route::get('b2b/email/verify/{id}/{hash}', function () {})->name('b2b.verification.verify');
