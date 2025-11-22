<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\LaporanInventarisController;

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/reset-password', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Products (only this one!)
    Route::resource('products', ProductController::class);
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions/add-to-cart', [TransactionController::class, 'addToCart'])->name('transactions.add');
    Route::delete('/transactions/remove-from-cart/{id}', [TransactionController::class, 'removeFromCart'])->name('transactions.remove');
    Route::post('/transactions/update-quantity', [TransactionController::class, 'updateQuantity'])->name('transactions.updateQuantity');

    Route::get('/keranjang', [TransactionController::class, 'cartView'])->name('cart.view');
    Route::post('/transactions/complete', [TransactionController::class, 'complete'])->name('transactions.complete');
    
    // Reports
// laporan keuangan
Route::prefix('reports/keuangan')->name('reports.keuangan.')->group(function(){
    Route::get('/', [LaporanKeuanganController::class,'index'])->name('index');
    Route::get('/create', [LaporanKeuanganController::class,'create'])->name('create');
    Route::post('/store', [LaporanKeuanganController::class,'store'])->name('store');
    Route::get('/{id}', [LaporanKeuanganController::class,'show'])->name('show');
});

// laporan inventaris
Route::prefix('reports/inventaris')->name('reports.inventaris.')->group(function(){
    Route::get('/', [LaporanInventarisController::class,'index'])->name('index');
    Route::get('/create', [LaporanInventarisController::class,'create'])->name('create');
    Route::post('/store', [LaporanInventarisController::class,'store'])->name('store');
    Route::get('/{id}', [LaporanInventarisController::class,'show'])->name('show');
});
});