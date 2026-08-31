<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\FrontendCraftController;
use App\Http\Controllers\Admin\CraftController as AdminCraftController;
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

// ─── Frontend: Crafts Directory ────────────────────────────────────────────
Route::get('/crafts',          [FrontendCraftController::class, 'index'])->name('crafts.index');
Route::get('/crafts/{slug}',   [FrontendCraftController::class, 'show'])->name('crafts.show');
