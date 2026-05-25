<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SubscriptionController;

// Gunakan nama rute yang spesifik dan pastikan mengarah ke Controller Web
Route::get('/', function () {
    return redirect()->route('customers.index');
});

Route::resource('customers', CustomerController::class)->except(['create', 'show', 'edit']);
Route::patch('customers/{id}/activate', [CustomerController::class, 'activate'])->name('customers.activate');
Route::patch('customers/{id}/deactivate', [CustomerController::class, 'deactivate'])->name('customers.deactivate');

Route::resource('services', ServiceController::class)->except(['create', 'show', 'edit']);
Route::patch('services/{id}/activate', [ServiceController::class, 'activate'])->name('services.activate');
Route::patch('services/{id}/deactivate', [ServiceController::class, 'deactivate'])->name('services.deactivate');

Route::resource('subscriptions', SubscriptionController::class)->except(['create', 'show', 'edit']);
Route::patch('subscriptions/{id}/status', [SubscriptionController::class, 'updateStatus'])->name('subscriptions.updateStatus');