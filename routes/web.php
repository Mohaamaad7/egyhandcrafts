<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\FrontendCraftController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\Admin\CraftController as AdminCraftController;
use App\Http\Controllers\Admin\WorkshopController as AdminWorkshopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/admin', function () {
    return view('admin.dashboard');
})->name('admin.dashboard')->middleware('auth');

// ─── Admin: Crafts CRUD ────────────────────────────────────────────────────
Route::prefix('admin/crafts')->name('admin.crafts.')->middleware('auth')->group(function () {
    Route::get('/',           [AdminCraftController::class, 'index'])->name('index');
    Route::get('/create',     [AdminCraftController::class, 'create'])->name('create');
    Route::post('/',          [AdminCraftController::class, 'store'])->name('store');
    Route::post('/upload-image', [AdminCraftController::class, 'uploadImage'])->name('upload-image');
    Route::get('/{craft}/edit',  [AdminCraftController::class, 'edit'])->name('edit');
    Route::put('/{craft}',    [AdminCraftController::class, 'update'])->name('update');
    Route::delete('/{craft}', [AdminCraftController::class, 'destroy'])->name('destroy');
});

// ─── Admin: Workshops CRUD ─────────────────────────────────────────────────
Route::prefix('admin/workshops')->name('admin.workshops.')->middleware('auth')->group(function () {
    Route::get('/',                [AdminWorkshopController::class, 'index'])->name('index');
    Route::get('/create',          [AdminWorkshopController::class, 'create'])->name('create');
    Route::post('/',               [AdminWorkshopController::class, 'store'])->name('store');
    Route::get('/{workshop}/edit', [AdminWorkshopController::class, 'edit'])->name('edit');
    Route::put('/{workshop}',      [AdminWorkshopController::class, 'update'])->name('update');
    Route::delete('/{workshop}',   [AdminWorkshopController::class, 'destroy'])->name('destroy');
});

// ─── Frontend: Crafts Directory ────────────────────────────────────────────
Route::get('/crafts',          [FrontendCraftController::class, 'index'])->name('crafts.index');
Route::get('/crafts/{slug}',   [FrontendCraftController::class, 'show'])->name('crafts.show');

// ─── Frontend: Interactive Map ─────────────────────────────────────────────
Route::get('/map',             [MapController::class, 'index'])->name('map.index');

// ─── Frontend: Workshop Profile ────────────────────────────────────────────
Route::get('/workshops/{slug}', [MapController::class, 'show'])->name('workshops.show');

