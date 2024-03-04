<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;

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

// Exports

Route::post('/answer-export', [ExportController::class, 'userAnswers']);
Route::post('/message-export', [ExportController::class, 'userMessages']);
Route::post('/users', [ExportController::class, 'users']);
Route::post('/users-frabo-status', [ExportController::class, 'usersFraboStatus']);
Route::post('/user-insights', [ExportController::class, 'userInsights']);
Route::post('/user-notifications', [ExportController::class, 'userNotifications']);
Route::post('/user-cluster-ranking', [ExportController::class, 'userClusterRanking']);
Route::post('/still-open-friend-requests', [ExportController::class, 'stillOpenFriendRequests']);
Route::post('/user-status-referrer', [ExportController::class, 'userStatusReferrer']);
