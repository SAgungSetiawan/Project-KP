<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataClientController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\StatistikController;
use App\Http\Controllers\InvoiceController;

// ============================================
// PUBLIC ROUTES (Guest Only)
// ============================================

// LOGIN PAGE (WELCOME)
Route::get('/', [AuthController::class, 'welcome'])->name('welcome');

// PROSES LOGIN
Route::post('/login', [AuthController::class, 'login'])->name('login');

// ============================================
// PROTECTED ROUTES (Authenticated Users)
// ============================================

Route::middleware(['auth'])->group(function () {
    
    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // ============================================
    // DASHBOARD
    // ============================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // ============================================
    // DATA CLIENT MANAGEMENT (CRUD)
    // ============================================
   Route::prefix('data-client')->name('data-client.')->group(function () {
    // LIST & SEARCH
    Route::get('/', [DataClientController::class, 'index'])->name('index');
    
    // EDIT FORM - Pindahkan ke atas sebelum {id}
    Route::get('/{id}/edit', [DataClientController::class, 'edit'])->name('edit');
    
    // SHOW DETAIL - Pindahkan ke bawah
    Route::get('/{id}', [DataClientController::class, 'show'])->name('show');
    
    // UPDATE DATA
    Route::put('/{id}', [DataClientController::class, 'update'])->name('update');
    
    // DELETE
    Route::delete('/{id}', [DataClientController::class, 'destroy'])->name('destroy');
    
    // UPDATE STATUS (ACTIVE / INACTIVE)
    Route::patch('/{id}/status', [DataClientController::class, 'updateStatus'])
         ->name('update-status');
});

// routes/web.php
Route::get('/invoices/{invoice}/show', [InvoiceController::class, 'show'])->name('invoices.show');
Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');

    // ============================================
    // ADD NEW CLIENT (Separate Controller)
    // ============================================
    Route::prefix('add-clients')->name('add-clients.')->group(function () {
        // CREATE FORM
        Route::get('/create', [ClientController::class, 'create'])->name('create');
        
        // STORE NEW CLIENT
        Route::post('/store', [ClientController::class, 'store'])->name('store');
    });
    
    // ============================================
    // STATISTICS
    // ============================================
    Route::prefix('statistik')->name('statistik.')->group(function () {
        // STATISTICS PAGE
        Route::get('/', [StatistikController::class, 'index'])->name('index');
        
        // GET STATISTICS DATA (API/AJAX)
        Route::get('/data', [StatistikController::class, 'getStatisticsData'])->name('data');
        
        // DOWNLOAD CSV
        Route::get('/download', [StatistikController::class, 'downloadCSV'])->name('download');
    });
});