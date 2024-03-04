<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MatchingController;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

//Matching
Route::post('/', [MatchingController::class, 'index']);
Route::get('/current', [MatchingController::class, 'getCurrent']);
Route::get('/current/status', [MatchingController::class, 'getCurrentStatus']);

