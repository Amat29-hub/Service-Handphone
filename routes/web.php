<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\ServiceController;
use App\Http\Controllers\Backend\HandphoneController;
use App\Http\Controllers\Backend\ServiceItemController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashboardController;

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

    // 🏠 Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | 👤 Users
    |--------------------------------------------------------------------------
    */
    Route::resource('users', UserController::class);
    Route::patch('users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::patch('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');

    /*
    |--------------------------------------------------------------------------
    | 📱 Handphone
    |--------------------------------------------------------------------------
    */
    Route::resource('handphone', HandphoneController::class);
    Route::patch('handphone/{id}/toggle-status', [HandphoneController::class, 'toggleStatus'])->name('handphone.toggle-status');
    Route::patch('handphone/{id}/restore', [HandphoneController::class, 'restore'])->name('handphone.restore');
    Route::delete('handphone/{id}/force-delete', [HandphoneController::class, 'forceDelete'])->name('handphone.force-delete');

    /*
    |--------------------------------------------------------------------------
    | ⚙️ Service Item
    |--------------------------------------------------------------------------
    */
    Route::resource('serviceitem', ServiceItemController::class);
    Route::patch('serviceitem/{id}/toggle-status', [ServiceItemController::class, 'toggleStatus'])->name('serviceitem.toggle-status');
    Route::patch('serviceitem/{id}/restore', [ServiceItemController::class, 'restore'])->name('serviceitem.restore');
    Route::delete('serviceitem/{id}/force-delete', [ServiceItemController::class, 'forceDelete'])->name('serviceitem.force-delete');

    /*
    |--------------------------------------------------------------------------
    | 🔧 Service
    |--------------------------------------------------------------------------
    */
    Route::resource('service', ServiceController::class);
    Route::get('service/{id}/payment', [ServiceController::class, 'payment'])->name('service.payment');
    Route::post('service/{id}/payment', [ServiceController::class, 'processPayment'])->name('service.payment.process');
    Route::post('service/{id}/cancel', [ServiceController::class, 'cancel'])->name('service.cancel');
    Route::patch('service/{id}/take', [ServiceController::class, 'take'])->name('service.take');
});