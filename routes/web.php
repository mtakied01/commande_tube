<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommandController;
use App\Http\Controllers\LogisticController;
use Illuminate\Support\Facades\Route;

    Route::resource('admin', AdminController::class);


// Route::view('/commandes','logisticPage.index');
// Route::view('/commander','tubePage.index');

Route::resource('tube', CommandController::class);
Route::resource('logistic', LogisticController::class);

