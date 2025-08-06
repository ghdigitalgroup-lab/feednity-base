<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\DashboardOverview;
use App\Livewire\StoreConnections;
use App\Livewire\FeedManager;
use App\Livewire\RuleEngine;
use App\Livewire\BillingOverview;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardOverview::class)->name('dashboard');
    Route::get('/stores', StoreConnections::class)->name('stores');
    Route::get('/feeds', FeedManager::class)->name('feeds');
    Route::get('/rules', RuleEngine::class)->name('rules');
    Route::get('/billing', BillingOverview::class)->name('billing');
});
