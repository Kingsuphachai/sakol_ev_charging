<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChargingStationController;

Route::get('/stations', [ChargingStationController::class, 'apiStations'])
    ->name('api.stations');
