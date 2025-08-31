<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChargingStationController;

// -------------------------------
// Dashboard สำหรับ User / Admin
// -------------------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])
        ->name('user.dashboard');
});

Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user && $user->role_id == 2) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('user.dashboard');
})->middleware(['auth'])->name('dashboard');

// -------------------------------
// หน้าแรก
// -------------------------------
Route::get('/', function () {
    return view('welcome');
});

// -------------------------------
// Profile
// -------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// -------------------------------
// สถานีชาร์จ (Stations)
// -------------------------------
Route::get('/stations', [ChargingStationController::class, 'index'])->name('stations.index');
Route::get('/stations/{id}', [ChargingStationController::class, 'show'])->whereNumber('id')->name('stations.show');

require __DIR__.'/auth.php';
