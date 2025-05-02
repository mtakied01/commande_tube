<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\administrationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CommandController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\LogisticController;
use App\Http\Controllers\testController;
use App\Http\Middleware\PermissionMiddleware;
use Illuminate\Support\Facades\Route;



Route::resource('admin', AdminController::class);



Route::resource('tube', CommandController::class)->middleware(PermissionMiddleware::class);
Route::resource('logistic', LogisticController::class);

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/', function () {
  return redirect()->route('login');
});
// Route::view('/home','home')->name('home');

Route::get('/test',[testController::class,'test']);

Route::post('/check',[testController::class,'check']);

Route::post('/check-product', [CommandController::class, 'checkProduct']);
Route::post('/validate-products', [CommandController::class, 'validateProducts']);

Route::get('/apn',[administrationController::class,'showApn'])->name('admin.apn');
Route::get('/rack',[administrationController::class,'showRack'])->name('admin.rack');