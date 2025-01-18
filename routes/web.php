<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\SsoController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\JenisPengajuanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ProfileController;


Route::get('/login',[AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('submitLogin');
Route::post('/authenticate-role', [AuthController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Dashboard for roles 1, 2, 3
Route::middleware(['role:1,2,3'])->group(function() {
    Route::get('/', function(){
        return view('layouts.pages.dashboard'); 
    })->name('index');
    Route::resource('notifikasi',NotifikasiController::class);
    Route::resource('profile',ProfileController::class);
});

// Otorisasi Karyawan (role:1)
Route::middleware(['role:1'])->group(function(){
    Route::resource('pengajuan', PengajuanController::class);
    Route::get('/pengajuan/{pjn_id}/{alternative}/{name}',[PengajuanController::class, 'detail'])->name('pengajuan.detail');
    Route::get('/pengajuan/download/{filename}', [PengajuanController::class, 'download'])->name('pengajuan.download');
    Route::put('/pengajuan/update-status/{id}/{decision?}', [PengajuanController::class, 'update_status'])->name('pengajuan.update_status');

});

// Otorisasi Human Resources (role:2) - Empty for now
Route::middleware(['role:2'])->group(function(){
    // You can add HR-specific routes here
});

// Otorisasi Admin (role:3) - Admin routes
Route::middleware(['role:3'])->group(function(){
    // Master Jabatan
    Route::resource('jabatan', JabatanController::class);
    Route::put('/jabatan/{id}/update_status', [JabatanController::class, 'update_status'])->name('jabatan.update_status');
    
    // Master Karyawan
    Route::resource('karyawan', KaryawanController::class);
    Route::put('/karyawan/{id}/update_status', [KaryawanController::class, 'update_status'])->name('karyawan.update_status');
    
    // Master SSO
    Route::resource('sso', SsoController::class);
    Route::put('/sso/{id}/update_status', [SsoController::class, 'update_status'])->name('sso.update_status');
    
    // Master Jenis Pengajuan
    Route::resource('jenis_pengajuan', JenisPengajuanController::class);
    Route::put('/jenis_pengajuan/{id}/update_status', [JenisPengajuanController::class, 'update_status'])->name('jenis_pengajuan.update_status');
});


