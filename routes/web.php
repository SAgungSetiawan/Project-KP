<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataClientController;
use App\Http\Controllers\ClientController; // Tambahkan ini

// Welcome Page
Route::get('/', [AuthController::class, 'welcome'])->name('welcome');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Dashboard Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Clients Management
    Route::prefix('data-client')->name('data-client.')->group(function () {
        Route::get('/', [DataClientController::class, 'index'])->name('index');
        Route::get('/{id}', [DataClientController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [DataClientController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DataClientController::class, 'update'])->name('update');
        Route::delete('/{id}', [DataClientController::class, 'destroy'])->name('destroy');
    });
    
    // Tambah Client Routes (menggunakan ClientController baru)
    Route::prefix('add-clients')->name('add-clients.')->group(function () {
        Route::get('/create', [ClientController::class, 'create'])->name('create');
        Route::post('/store', [ClientController::class, 'store'])->name('store');
    });
    
    // Statistics
    Route::get('/statistics', [DashboardController::class, 'statistics'])->name('dashboard.statistics');
});