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

// Bus reading — public so guests can search on landing screen
Route::get('/buses',        [BusController::class, 'index']);
Route::get('/search-buses', [BusController::class, 'search']);

// ── Authenticated routes ───────────────────────────────────────────────────────

Route::middleware('auth:sanctum')->group(function () {

    // Auth user info
    Route::get('/user',  fn(Request $r) => $r->user());
    Route::get('/users', [AuthController::class, 'listUsers']);

    // Bus write operations
    Route::post('/buses',        [BusController::class, 'store']);
    Route::put('/buses/{id}',    [BusController::class, 'update']);
    Route::delete('/buses/{id}', [BusController::class, 'delete']);

    // Live location update
    Route::post('/bus/update-location', [BusController::class, 'updateLocation']);

    // ── Phase 2: Driver-Bus Assignment routes ─────────────────────────────────

    // Driver fetches their assigned bus
    // Returns the bus where driver_id = authenticated user's id
    Route::get('/my-bus', [BusController::class, 'myBus']);

    // Admin assigns a driver to a bus
    // Body: { bus_id, driver_id }
    Route::post('/assign-bus', [BusController::class, 'assignBus']);

    // Admin unassigns a driver from a bus
    Route::post('/unassign-bus', [BusController::class, 'unassignBus']);
});
