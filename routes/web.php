<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\HandphoneController;

// Dashboard
Route::view('/', 'page.backend.dashboard.index')->name('dashboard');

// Users
Route::resource('users', UserController::class);
Route::patch('users/{id}/toggle-status', [UserController::class, 'toggleStatus']);

// Handphone
Route::resource('handphone', \App\Http\Controllers\Backend\HandphoneController::class);
Route::post('handphone/{id}/toggle-status', [\App\Http\Controllers\Backend\HandphoneController::class, 'toggleStatus']);
