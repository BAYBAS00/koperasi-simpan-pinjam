<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AngsuranController;
use App\Http\Controllers\PengurusController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\SimpananDetailController;
use App\Http\Controllers\SimpananMasterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:pengurus'])
    ->prefix('mgmt')
    ->name('mgmt.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | USER MANAGEMENT
        |--------------------------------------------------------------------------
        */

        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');

        Route::put('/users/{user}', [UserController::class, 'update'])
            ->name('users.update');

        Route::delete('/users/{user}', [UserController::class, 'destroy'])
            ->name('users.destroy');

        /*
        |--------------------------------------------------------------------------
        | MASTER DATA
        |--------------------------------------------------------------------------
        */

        Route::resource('pengurus', PengurusController::class)
            ->parameters([
                'pengurus' => 'pengurus',
            ])
            ->only(['index', 'store', 'destroy']);

        Route::resource('anggota', AnggotaController::class)
            ->only(['index', 'store', 'update', 'destroy']);

        /*
        |--------------------------------------------------------------------------
        | TRANSAKSI
        |--------------------------------------------------------------------------
        */

        Route::resource('pinjaman', PinjamanController::class)
            ->only(['index', 'show', 'update', 'destroy']);

        Route::resource('angsuran', AngsuranController::class)
            ->only(['index', 'store', 'destroy']);

        Route::resource('simpanan-master', SimpananMasterController::class)
            ->only(['index', 'store', 'destroy']);

        Route::resource('simpanan-detail', SimpananDetailController::class)
            ->only(['index', 'store', 'destroy']);

    });
