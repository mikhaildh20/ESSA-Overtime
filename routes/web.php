<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\JenisPengajuanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\SsoController;


Route::get('/', function(){
    return view('layouts.pages.dashboard');
})->name('index');

Route::resource('jabatan',JabatanController::class);
Route::put('/jabatan/{id}/update_status', [JabatanController::class, 'update_status'])->name('jabatan.update_status');

Route::resource('karyawan',KaryawanController::class);
Route::put('/karyawan/{id}/update_status', [KaryawanController::class, 'update_status'])->name('karyawan.update_status');

Route::resource('sso',SsoController::class);
Route::put('/sso/{id}/update_status', [SsoController::class, 'update_status'])->name('sso.update_status');

Route::resource('pengajuan',PengajuanController::class);

Route::resource('jenis',JenisPengajuanController::class);
Route::put('/jenis/{id}/update_status', [JenisPengajuanController::class, 'update_status'])->name('jenis.update_status');
// Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
