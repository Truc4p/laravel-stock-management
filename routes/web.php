<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\StockMovementController;

// For demo purposes, we'll create a simple route without authentication
Route::get('/', [DashboardController::class, 'index'])->name('home');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Resource routes  
Route::resource('categories', CategoryController::class);
Route::resource('suppliers', SupplierController::class);
Route::resource('warehouses', WarehouseController::class);
Route::resource('products', ProductController::class);
Route::resource('inventory', InventoryController::class)->except(['create', 'edit', 'update', 'destroy']);
Route::resource('stock-movements', StockMovementController::class)->only(['index', 'show']);

// Inventory management routes
Route::get('/inventory/move', [InventoryController::class, 'move'])->name('inventory.move');
Route::post('/inventory/move', [InventoryController::class, 'processMovement'])->name('inventory.process-movement');
Route::post('/inventory/{inventory}/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');
