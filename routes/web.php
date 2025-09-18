<?php

use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// TAMBAH INI - Fix untuk POST admin/login yang hilang
Route::post('admin/login', function(\Illuminate\Http\Request $request) {
    // Redirect ke GET login dengan input data untuk Filament handle
    return redirect('admin/login')->withInput()->withErrors([]);
})->middleware(['web']);

// Route frontend existing
Route::get('/',[FrontController::class,'index'])->name('front.index');
Route::get('/category/{category:slug}',[FrontController::class,'category'])->name('front.category');
Route::get('/details/{property:slug}',[FrontController::class,'details'])->name('front.details');
Route::get('/search',[FrontController::class,'search'])->name('front.search');

Route::post('/customer/store', [FrontController::class, 'storeCustomer'])->name('front.customer.store');

// Dashboard route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes (uncomment jika diperlukan)
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
