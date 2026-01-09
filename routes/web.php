<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataClientController;
use App\Http\Controllers\ClientController; // Tambahkan ini
use App\Http\Controllers\StatistikController;

// LOGIN PAGE (WELCOME)
Route::get('/', [AuthController::class, 'welcome'])->name('welcome');

// PROSES LOGIN
Route::post('/login', [AuthController::class, 'login'])->name('login');

// LOGOUT
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

    // ✅ UPDATE STATUS (ACTIVE / INACTIVE)
    Route::patch('/{id}/status', 
        [DataClientController::class, 'updateStatus']
    )->name('update-status');

});

    
    // Tambah Client Routes (menggunakan ClientController baru)
    Route::prefix('add-clients')->name('add-clients.')->group(function () {
        Route::get('/create', [ClientController::class, 'create'])->name('create');
        Route::post('/store', [ClientController::class, 'store'])->name('store');
    });
    
    // Statistics
    //Route::get('/statistics', [DashboardController::class, 'statistics'])->name('dashboard.statistics');


    Route::prefix('statistik')->name('statistik.')->group(function () {
    Route::get('/', [StatistikController::class, 'index'])->name('index');
    Route::get('/data', [StatistikController::class, 'getStatisticsData'])->name('data');
    Route::get('/download', [StatistikController::class, 'downloadCSV'])->name('download');
});
});