<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\PackagingMaterialController;
use App\Http\Controllers\ReceivingController;
use App\Http\Controllers\MaterialIssueController;
use App\Http\Controllers\ProductionBatchController;
use App\Http\Controllers\QualityController;
use App\Http\Controllers\StockAuditController;
use App\Http\Controllers\TraceabilityController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\UserController;

// ─── Public Routes ──────────────────────────────────────────
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/quick-login/{role}', [AuthController::class, 'quickLogin'])->name('quick-login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public Consumer & Retail QR Traceability Page (Mobile Optimized)
Route::get('/trace/{batchId}', [TraceabilityController::class, 'publicTrace'])->name('trace.public');

// ─── Authenticated Factory Management Routes ────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ─── 1. Admin Only (System Administration, Users & Full Audit) ───
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
    });

    Route::middleware(['role:admin,viewer'])->group(function () {
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit.index');
    });

    // ─── 2. Production Department (Batches, Products & BOMs) ───
    Route::middleware(['role:admin,production_manager'])->group(function () {
        Route::get('/production/create', [ProductionBatchController::class, 'create'])->name('production.create');
        Route::post('/production', [ProductionBatchController::class, 'store'])->name('production.store');
        Route::get('/production/{batch}/edit', [ProductionBatchController::class, 'edit'])->name('production.edit');
        Route::put('/production/{batch}', [ProductionBatchController::class, 'update'])->name('production.update');
        Route::post('/production/{batch}/actuals', [ProductionBatchController::class, 'updateActuals'])->name('production.actuals');
        Route::delete('/production/{batch}', [ProductionBatchController::class, 'destroy'])->name('production.destroy');

        // Products creation & recipe edit
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    Route::middleware(['role:admin,production_manager,quality_control,viewer'])->group(function () {
        Route::get('/production', [ProductionBatchController::class, 'index'])->name('production.index');
        Route::get('/production/{batch}', [ProductionBatchController::class, 'show'])->name('production.show');
        Route::get('/production/{batch}/traveler', [ProductionBatchController::class, 'printTraveler'])->name('production.traveler');

        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::get('/traceability/backward', [TraceabilityController::class, 'backward'])->name('traceability.backward');
    });

    // ─── 3. Quality Control Department (Lab Tests, Release & COA) ───
    Route::middleware(['role:admin,quality_control'])->group(function () {
        Route::get('/quality/create', [QualityController::class, 'create'])->name('quality.create');
        Route::post('/quality', [QualityController::class, 'store'])->name('quality.store');
        Route::get('/quality/{quality}/edit', [QualityController::class, 'edit'])->name('quality.edit');
        Route::put('/quality/{quality}', [QualityController::class, 'update'])->name('quality.update');
        Route::delete('/quality/{quality}', [QualityController::class, 'destroy'])->name('quality.destroy');
        Route::post('/quality/release/{batch}', [QualityController::class, 'releaseBatch'])->name('quality.release');

        // Recall Simulator (Crisis Management)
        Route::get('/traceability/recall', [TraceabilityController::class, 'recall'])->name('traceability.recall');

        // Documents & Certificates Upload
        Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    });

    Route::middleware(['role:admin,quality_control,viewer'])->group(function () {
        Route::get('/quality', [QualityController::class, 'index'])->name('quality.index');
        Route::get('/quality/{quality}', [QualityController::class, 'show'])->name('quality.show');
        Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    });

    // ─── 4. Warehouse Department (Receiving GRN, Raw Materials, Packaging, Audits) ───
    Route::middleware(['role:admin,warehouse'])->group(function () {
        Route::resource('receiving', ReceivingController::class);
        Route::resource('stock-audit', StockAuditController::class);

        Route::get('/raw-materials/create', [RawMaterialController::class, 'create'])->name('raw-materials.create');
        Route::post('/raw-materials', [RawMaterialController::class, 'store'])->name('raw-materials.store');
        Route::get('/raw-materials/{raw_material}/edit', [RawMaterialController::class, 'edit'])->name('raw-materials.edit');
        Route::put('/raw-materials/{raw_material}', [RawMaterialController::class, 'update'])->name('raw-materials.update');
        Route::delete('/raw-materials/{raw_material}', [RawMaterialController::class, 'destroy'])->name('raw-materials.destroy');

        Route::get('/packaging/create', [PackagingMaterialController::class, 'create'])->name('packaging.create');
        Route::post('/packaging', [PackagingMaterialController::class, 'store'])->name('packaging.store');
        Route::get('/packaging/{packaging}/edit', [PackagingMaterialController::class, 'edit'])->name('packaging.edit');
        Route::put('/packaging/{packaging}', [PackagingMaterialController::class, 'update'])->name('packaging.update');
        Route::delete('/packaging/{packaging}', [PackagingMaterialController::class, 'destroy'])->name('packaging.destroy');

        Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
        Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
        Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

        Route::post('/material-issues', [MaterialIssueController::class, 'store'])->name('material-issues.store');
    });

    Route::middleware(['role:admin,warehouse,quality_control,viewer'])->group(function () {
        Route::get('/raw-materials', [RawMaterialController::class, 'index'])->name('raw-materials.index');
        Route::get('/raw-materials/{raw_material}', [RawMaterialController::class, 'show'])->name('raw-materials.show');
        Route::get('/packaging', [PackagingMaterialController::class, 'index'])->name('packaging.index');
        Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
        Route::get('/traceability/forward', [TraceabilityController::class, 'forward'])->name('traceability.forward');
    });

    // Material issues logs
    Route::middleware(['role:admin,warehouse,production_manager'])->group(function () {
        Route::get('/material-issues', [MaterialIssueController::class, 'index'])->name('material-issues.index');
    });

    // Barcode & QR Center (Warehouse, Production, Quality, Admin)
    Route::middleware(['role:admin,warehouse,production_manager,quality_control'])->group(function () {
        Route::get('/barcodes', [BarcodeController::class, 'index'])->name('barcodes.index');
        Route::get('/barcodes/label', [BarcodeController::class, 'printLabel'])->name('barcodes.label');
    });
});
