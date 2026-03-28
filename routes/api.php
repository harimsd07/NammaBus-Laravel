<?php

use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ── Public routes ─────────────────────────────────────────────────────────────

Route::post('/login',    [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [PasswordResetController::class, 'reset']);

Route::get('auth/{provider}/redirect', [AuthController::class, 'redirectToProvider']);
Route::get('auth/{provider}/callback', [AuthController::class, 'handleProviderCallback']);

Route::get('/buses',        [BusController::class, 'index']);
Route::get('/search-buses', [BusController::class, 'search']);

// ── Authenticated routes ───────────────────────────────────────────────────────

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user',  fn(Request $r) => $r->user());
    Route::get('/users', [AuthController::class, 'listUsers']);

    // Bus management
    Route::post('/buses',        [BusController::class, 'store']);
    Route::put('/buses/{id}',    [BusController::class, 'update']);
    Route::delete('/buses/{id}', [BusController::class, 'delete']);

    // Live location
    Route::post('/bus/update-location', [BusController::class, 'updateLocation']);

    // Driver-bus assignment
    Route::get('/my-bus',        [BusController::class, 'myBus']);
    Route::post('/assign-bus',   [BusController::class, 'assignBus']);
    Route::post('/unassign-bus', [BusController::class, 'unassignBus']);

    // Phase 3 — Delay reporting
    Route::post('/bus/report-delay', [BusController::class, 'reportDelay']);
    Route::post('/bus/clear-delay',  [BusController::class, 'clearDelay']);
});


Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});
