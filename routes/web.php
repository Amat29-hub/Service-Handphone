<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\ServiceController;
use App\Http\Controllers\Backend\HandphoneController;
use App\Http\Controllers\Backend\ServiceItemController;
use App\Http\Controllers\Backend\AuthController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Backend Routes
|--------------------------------------------------------------------------
| Semua route di bawah ini hanya bisa diakses kalau sudah login.
| Jika belum login, user akan otomatis diarahkan ke halaman login.
*/
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::view('/', 'page.backend.dashboard.index')->name('dashboard');

    // Users
    Route::resource('users', UserController::class);
    Route::patch('users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Handphone
    Route::resource('handphone', HandphoneController::class);
    Route::patch('handphone/{id}/toggle-status', [HandphoneController::class, 'toggleStatus'])->name('handphone.toggle-status');

    // Service Item
    Route::resource('serviceitem', ServiceItemController::class);
    Route::patch('serviceitem/{id}/toggle-status', [ServiceItemController::class, 'toggleStatus'])->name('serviceitem.toggle-status');

    // Service
    Route::resource('service', ServiceController::class);
    Route::get('/service/{id}/payment', [ServiceController::class, 'payment'])->name('service.payment');
    Route::post('/service/{id}/payment', [ServiceController::class, 'processPayment'])->name('service.payment.process');
    Route::get('/service/{id}/cetak-struk', [ServiceController::class, 'cetakStruk'])->name('service.cetakStruk');
});