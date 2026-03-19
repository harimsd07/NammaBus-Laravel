<?php

use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// authentication
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/users', [AuthController::class, 'listUsers']);


// Logic: Routes for requesting a reset and submitting the new password
Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [PasswordResetController::class, 'reset']);


Route::get('/buses',[BusController::class,'index']);

Route::post('/buses', [BusController::class, 'store']);

Route::middleware('auth:sanctum')->post('/bus/update-location', [BusController::class, 'updateLocation']);



Route::get('/search-buses', [BusController::class, 'search']);



/*
|--------------------------------------------------------------------------
| Social Auth Routes [Added at the bottom for Google and GitHub flow]
|--------------------------------------------------------------------------
*/
Route::get('auth/{provider}/redirect', [AuthController::class, 'redirectToProvider']);
Route::get('auth/{provider}/callback', [AuthController::class, 'handleProviderCallback']);


// After
Route::middleware('auth:sanctum')->get('/users', [AuthController::class, 'listUsers']);
