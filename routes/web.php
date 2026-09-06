<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\FrontendCraftController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\CraftsmanStoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CraftController as AdminCraftController;
use App\Http\Controllers\Admin\WorkshopController as AdminWorkshopController;
use App\Http\Controllers\Admin\CraftsmanStoryController as AdminStoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Middleware\EnsureSuperAdmin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard')->middleware('auth');

// ─── Admin: Personal Profile & Credentials ──────────────────────────────────
Route::prefix('admin/profile')->name('admin.profile.')->middleware('auth')->group(function () {
    Route::get('/',              [AdminProfileController::class, 'edit'])->name('edit');
    Route::put('/',              [AdminProfileController::class, 'update'])->name('update');
    Route::put('/password',      [AdminProfileController::class, 'updatePassword'])->name('password');
});

// ─── Admin: Users & Team CRUD ──────────────────────────────────────────────
Route::prefix('admin/users')->name('admin.users.')->middleware(['auth', EnsureSuperAdmin::class])->group(function () {
    Route::get('/',             [AdminUserController::class, 'index'])->name('index');
    Route::get('/create',       [AdminUserController::class, 'create'])->name('create');
    Route::post('/',            [AdminUserController::class, 'store'])->name('store');
    Route::get('/{user}/edit',  [AdminUserController::class, 'edit'])->name('edit');
    Route::put('/{user}',       [AdminUserController::class, 'update'])->name('update');
    Route::delete('/{user}',    [AdminUserController::class, 'destroy'])->name('destroy');
});

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

// ─── Admin: Craftsmen Stories CRUD ─────────────────────────────────────────
Route::prefix('admin/stories')->name('admin.stories.')->middleware('auth')->group(function () {
    Route::get('/',              [AdminStoryController::class, 'index'])->name('index');
    Route::get('/create',        [AdminStoryController::class, 'create'])->name('create');
    Route::post('/',             [AdminStoryController::class, 'store'])->name('store');
    Route::get('/{story}/edit',  [AdminStoryController::class, 'edit'])->name('edit');
    Route::put('/{story}',       [AdminStoryController::class, 'update'])->name('update');
    Route::delete('/{story}',    [AdminStoryController::class, 'destroy'])->name('destroy');
});

// ─── Frontend: Crafts Directory ────────────────────────────────────────────
Route::get('/crafts',          [FrontendCraftController::class, 'index'])->name('crafts.index');
Route::get('/crafts/{slug}',   [FrontendCraftController::class, 'show'])->name('crafts.show');

// ─── Frontend: Interactive Map ─────────────────────────────────────────────
Route::get('/map',             [MapController::class, 'index'])->name('map.index');

// ─── Frontend: Workshop Profile ────────────────────────────────────────────
Route::get('/workshops/{slug}', [MapController::class, 'show'])->name('workshops.show');

// ─── Frontend: Craftsmen Stories & Testimonials ────────────────────────────
Route::get('/stories',          [CraftsmanStoryController::class, 'index'])->name('stories.index');
Route::get('/stories/{slug}',   [CraftsmanStoryController::class, 'show'])->name('stories.show');


