<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::post('/order/{order}/discount', [OrderController::class, 'apply'])->middleware('idempotent');
