<?php

use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/',[FrontController::class,'index'])->name('front.index');
Route::get('/category/{category:slug}',[FrontController::class,'category'])->name('front.category');
Route::get('/details/{property:slug}',[FrontController::class,'details'])->name('front.details');
Route::get('/search',[FrontController::class,'search'])->name('front.search');

Route::post('/customer/store', [FrontController::class, 'storeCustomer'])->name('front.customer.store');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });


// Tambahkan di routes/web.php
Route::get('/storage/abouts/{filename}', function ($filename) {
    $path = storage_path('abouts/' . $filename);
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    return response()->file($path);
})->where('filename', '.*');
require __DIR__.'/auth.php';
