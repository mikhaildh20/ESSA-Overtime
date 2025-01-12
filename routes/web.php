<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\SsoController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\JenisPengajuanController;

Route::middleware('guest')->group(function () {
    Route::get('/login',[AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/', function(){
    return view('layouts.pages.dashboard');
})->name('index');

Route::middleware(['auth','role:1'])->group(function(){
    Route::resource('pengajuan',PengajuanController::class);
});

Route::middleware(['auth','role:2'])->group(function(){
    Route::resource('jabatan',JabatanController::class);
    Route::put('/jabatan/{id}/update_status', [JabatanController::class, 'update_status'])->name('jabatan.update_status');
    
    Route::resource('karyawan',KaryawanController::class);
    Route::put('/karyawan/{id}/update_status', [KaryawanController::class, 'update_status'])->name('karyawan.update_status');
    
    Route::resource('sso',SsoController::class);
    Route::put('/sso/{id}/update_status', [SsoController::class, 'update_status'])->name('sso.update_status');
    
    Route::resource('jenis_pengajuan',JenisPengajuanController::class);
    Route::put('/jenis_pengajuan/{id}/update_status', [JenisPengajuanController::class, 'update_status'])->name('jenis_pengajuan.update_status');
});

Route::middleware(['auth','role:3'])->group(function(){

});



// Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
