<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Payments\MidtransController;

// Payment webhooks (no auth, no CSRF)
Route::post('/payments/midtrans/notify', [MidtransController::class, 'notify']);
