<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\InventoryTransactionController;
use App\Http\Controllers\FinancialTransactionController;
use App\Http\Controllers\ReportController;

// ============================================
// AUTH ROUTES (Guest)
// ============================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ============================================
// AUTHENTICATED ROUTES
// ============================================
Route::middleware('auth')->group(function () {

    // Redirect root to dashboard
    Route::get('/', fn () => redirect()->route('dashboard'));

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kategori (CRUD)
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Barang (CRUD)
    Route::resource('items', ItemController::class)->except(['show']);

    // Transaksi Stok (Barang Masuk / Keluar)
    Route::get('/inventory', [InventoryTransactionController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [InventoryTransactionController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [InventoryTransactionController::class, 'store'])->name('inventory.store');

    // Transaksi Keuangan
    Route::get('/finance', [FinancialTransactionController::class, 'index'])->name('finance.index');
    Route::get('/finance/create', [FinancialTransactionController::class, 'create'])->name('finance.create');
    Route::post('/finance', [FinancialTransactionController::class, 'store'])->name('finance.store');
    Route::delete('/finance/{finance}', [FinancialTransactionController::class, 'destroy'])->name('finance.destroy');

    // Laporan
    Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('/reports/financial', [ReportController::class, 'financial'])->name('reports.financial');

    // Export PDF
    Route::get('/reports/inventory/pdf', [ReportController::class, 'inventoryPdf'])->name('reports.inventory.pdf');
    Route::get('/reports/financial/pdf', [ReportController::class, 'financialPdf'])->name('reports.financial.pdf');
});
