<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::prefix('admin')->group(function () {
    Route::resource('products', ProductController::class);
});
