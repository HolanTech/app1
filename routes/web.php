<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OtbPersiteNToNController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// Authentication Routes...
Auth::routes(['verify' => true]); // Enable email verification if needed by adding 'verify' => true

// Protected routes that require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/maps', [HomeController::class, 'show'])->name('maps');

    // Using the 'resource' method for CRUD operations on customers
    Route::resource('customer', CustomerController::class);
    // Route::resource harus diatas route khusus untuk menghindari conflict
    Route::resource('upload', UploadController::class);
    // Definisikan route untuk download secara terpisah
    Route::get('upload/{upload}/download', [UploadController::class, 'download'])->name('upload.download');

    // Additional route for changing customer status, with explicit binding
    Route::post('/customer/status/{customer}', [CustomerController::class, 'changeStatus'])->name('customer.changeStatus');

    // Resource route for OTB Persite N to N functionality
    Route::resource('otb_persite_n_to_n', OtbPersiteNToNController::class);
});

// Additional configurations or route groups can be added below as needed
