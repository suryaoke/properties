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



// Di routes/web.php, ganti route yang ada dengan:
Route::get('/storage/abouts/{filename}', function ($filename) {
    // Path yang benar sesuai dengan lokasi file Anda
    $path = base_path('storage/abouts/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    // Set header yang tepat untuk SVG
    $mimeType = mime_content_type($path);

    return response()->file($path, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000', // Cache 1 tahun
    ]);
})->where('filename', '.*');
require __DIR__.'/auth.php';
