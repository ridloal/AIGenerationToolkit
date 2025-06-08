<?php

use App\Http\Controllers\AiSettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectAssetController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Home Sederhana
Route::get('/', function () {
    return view('welcome'); // View bawaan Laravel, bisa Anda ganti
})->name('home');

// Dashboard (sudah ada dari Breeze, pastikan viewnya menggunakan layout admin)
Route::get('/dashboard', function () {
    // return view('dashboard'); // Ini view Breeze standar
    return view('admin.dashboard'); // Ganti ke view dashboard admin kita
})->middleware(['auth', 'verified'])->name('dashboard');

// Auth routes (sudah ada dari Breeze)
require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Menggunakan Route::resource untuk menangani semua aksi CRUD Proyek
    Route::resource('projects', ProjectController::class);

    // Project Assets Routes
    Route::get('/projects/{project}/assets', [ProjectAssetController::class, 'index'])->name('projects.assets.index');
    Route::post('/projects/{project}/assets', [ProjectAssetController::class, 'store'])->name('projects.assets.store');
    Route::delete('/projects/assets/{asset}', [ProjectAssetController::class, 'destroy'])->name('projects.assets.destroy');
    
    // Rute untuk mengaktifkan project & generate AI tetap terpisah
    Route::post('/projects/{project}/activate', [ProjectController::class, 'activate'])->name('projects.activate');
    Route::post('/ai/generate', [ProjectController::class, 'generate'])->name('ai.generate');
});

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/settings/ai', [AiSettingController::class, 'index'])->name('settings.ai.index');
    Route::post('/settings/ai', [AiSettingController::class, 'store'])->name('settings.ai.store');
});

// Contoh routing ke modul (jika Anda menggunakan nWidart/laravel-modules)
// Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
//     // Route::get('/toolkit', [\Modules\ToolKit\Http\Controllers\ToolKitController::class, 'index'])->name('toolkit.index');
// });