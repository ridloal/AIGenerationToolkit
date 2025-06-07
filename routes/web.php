<?php

use App\Http\Controllers\ProfileController;
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

// Profile routes (sudah ada dari Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Contoh routing ke modul (jika Anda menggunakan nWidart/laravel-modules)
// Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
//     // Route::get('/toolkit', [\Modules\ToolKit\Http\Controllers\ToolKitController::class, 'index'])->name('toolkit.index');
// });