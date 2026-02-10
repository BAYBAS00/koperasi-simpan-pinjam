<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\{
//     UserController,
//     PengurusController,
//     AnggotaController,
//     PinjamanController,
//     AngsuranController,
//     SimpananMasterController,
//     SimpananDetailController
// };

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

// Route::get('/', function () {
//     return view('layouts.app');
// });

// Route::resource('users', UserController::class);
// Route::resource('pengurus', PengurusController::class);
// Route::resource('anggota', AnggotaController::class);
// Route::resource('pinjaman', PinjamanController::class);
// Route::resource('angsuran', AngsuranController::class);
// Route::resource('simpanan_master', SimpananMasterController::class);
// Route::resource('simpanan_detail', SimpananDetailController::class);
