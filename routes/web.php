<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OnboardingController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/onboarding', [OnboardingController::class, 'create']);
Route::post('/onboarding', [OnboardingController::class, 'store']);
