<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;

// Route::get('/', [PageController::class, 'index']);
Route::get('/', [JabatanController::class, 'index']);
Route::resource('jabatan',[JabatanController::class]);
Route::resource('karyawan',[KaryawanController::class]);


// Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
