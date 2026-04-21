<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DateCalculatorController;


Route::get('/calculate-dates', DateCalculatorController::class);