<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChargingStationController;
use App\Http\Controllers\Admin\StationController; // ✅ เอาอันนี้อันเดียวพอ

// -------------------------------
// Admin (ต้องเป็นแอดมินเท่านั้น)
// -------------------------------
Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // ✅ CRUD สถานี (resource จะสร้าง route:
// admin.stations.index/create/store/show/edit/update/destroy)
        Route::resource('stations', StationController::class);

        // ✅ จัดการผู้ใช้ (ถ้ายังไม่มีคอนโทรลเลอร์ ก็เก็บแบบนี้ไว้ก่อน)
        Route::get('/users', [AdminController::class, 'users'])->name('users');
    });

// -------------------------------
// User Dashboard + แผนที่รวม
// -------------------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/stations/map', [ChargingStationController::class, 'map'])->name('stations.map');
});

// API สำหรับหน้าแผนที่
Route::get('/api/stations', [ChargingStationController::class, 'apiStations'])->name('api.stations');

// -------------------------------
// Smart redirect ตาม role
// -------------------------------
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
Route::get('/', fn() => view('welcome'));

// -------------------------------
// Profile
// -------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// -------------------------------
// สถานีชาร์จ (public pages)
// -------------------------------
Route::get('/stations', [ChargingStationController::class, 'index'])->name('stations.index');
Route::get('/stations/{id}', [ChargingStationController::class, 'show'])
    ->whereNumber('id')->name('stations.show');

require __DIR__.'/auth.php';
