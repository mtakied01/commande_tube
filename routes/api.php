<?php

use App\Http\Controllers\CommandController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\testController;

Route::get('/check', [testController::class, 'check']);

Route::post('/check-product', [CommandController::class, 'checkProduct']);
