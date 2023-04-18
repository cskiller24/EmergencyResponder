<?php

use App\Http\Controllers\Api\v1\Admin\EmergencyTypeController;
use App\Http\Controllers\Api\v1\Auth\{LoginController, RegisterController, UserController};
use App\Http\Controllers\Api\v1\InviteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'v1', 'as' => 'v1'], function () {
    Route::post('/login', LoginController::class);
    Route::post('/register', RegisterController::class);

    Route::patch('/invites/{invite:code}/resend', [InviteController::class, 'resend']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', UserController::class);

        Route::get('/invites', [InviteController::class, 'index']);
        Route::post('/invites', [InviteController::class, 'store']);
        Route::delete('/invites/{invite:code}', [InviteController::class, 'destroy']);

        Route::group(['middleware' => 'role:admin', 'prefix' => 'admin'], function () {
            Route::apiResource('emergency-types', EmergencyTypeController::class);
        });
    });
});
