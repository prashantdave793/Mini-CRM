<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VonageController;
use App\Http\Controllers\ActivityLogController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [CustomerController::class,'dashboard'])->name('dashboard');

    Route::resource('customers', CustomerController::class);
    Route::post('customers/{customer}/send-sms', [VonageController::class, 'sendSms'])->name('customers.sendSms');
    Route::post('customers/{customer}/call', [VonageController::class, 'call'])->name('customers.call');

    Route::get('logs', [ActivityLogController::class, 'index'])->name('logs.index');
});
Route::post('/webhooks/answer', [VonageController::class, 'answerWebhook'])->name('vonage.answer');
Route::post('/webhooks/event', [VonageController::class, 'eventWebhook'])->name('vonage.event');

require __DIR__.'/auth.php';
