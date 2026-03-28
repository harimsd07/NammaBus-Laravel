<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// ── Public admin login ────────────────────────────────────────────────────────
Route::get('/admin/login',  [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');

// ── Protected admin routes ────────────────────────────────────────────────────
Route::prefix('admin')
    ->middleware('admin.auth')
    ->name('admin.')
    ->group(function () {

    Route::get('/',        [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/buses',   [AdminController::class, 'buses'])->name('buses');
    Route::get('/drivers', [AdminController::class, 'drivers'])->name('drivers');
    Route::get('/users',   [AdminController::class, 'users'])->name('users');

    // Bus management
    Route::post('/buses',          [AdminController::class, 'storeBus'])->name('buses.store');
    Route::put('/buses/{id}',      [AdminController::class, 'updateBus'])->name('buses.update');
    Route::delete('/buses/{id}',   [AdminController::class, 'deleteBus'])->name('buses.delete');

    // Driver-bus assignment
    Route::post('/assign-bus',   [AdminController::class, 'assignBus'])->name('assign');
    Route::post('/unassign-bus', [AdminController::class, 'unassignBus'])->name('unassign');

    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});
