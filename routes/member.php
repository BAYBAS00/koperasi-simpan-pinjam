<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AngsuranController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\SimpananDetailController;
use App\Http\Controllers\SimpananMasterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth','role:anggota'])
    ->prefix('member')
    ->name('member.')
    ->group(function () {


        /*
        |--------------------------------------------------------------------------
        | SELF USER VIEW
        |--------------------------------------------------------------------------
        */

        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        /*
        |--------------------------------------------------------------------------
        | PINJAMAN
        |--------------------------------------------------------------------------
        */

        Route::controller(PinjamanController::class)
            ->prefix('pinjaman')
            ->name('pinjaman.')
            ->group(function () {

                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{pinjaman}', 'show')->name('show');

            });

        /*
        |--------------------------------------------------------------------------
        | READ ONLY
        |--------------------------------------------------------------------------
        */

        Route::get('/angsuran', [AngsuranController::class, 'index'])
            ->name('angsuran.index');

        Route::get('/simpanan_master', [SimpananMasterController::class, 'index'])
            ->name('simpanan_master.index');

        Route::get('/simpanan_detail', [SimpananDetailController::class, 'index'])
            ->name('simpanan_detail.index');

    });
