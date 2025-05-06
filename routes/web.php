<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\administrationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CommandController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\historicDashboard;
use App\Http\Controllers\LogisticController;
use App\Http\Controllers\testController;
use App\Http\Middleware\PermissionMiddleware;
use Illuminate\Support\Facades\Route;



Route::resource('admin', AdminController::class);

Route::resource('tube', CommandController::class)->middleware(PermissionMiddleware::class);
Route::resource('logistic', LogisticController::class);



Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::get('/', function () {
  return redirect()->route('login');
});
Route::get('/test',[testController::class,'test']);
Route::get('/apn',[administrationController::class,'showApn'])->name('admin.apn');
Route::get('/rack',[administrationController::class,'showRack'])->name('admin.rack');
Route::get('/dash',[historicDashboard::class,'index'])->name('admin.history');


Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/check',[testController::class,'check']);
Route::post('/check-product', [CommandController::class, 'checkProduct']);
Route::post('/validate-products', [CommandController::class, 'validateProducts']);
Route::post('/apn',[administrationController::class,'addApn'])->name('apn.create');
Route::post('/rack',[administrationController::class,'searchRack'])->name('admin.searchRack');
Route::post('/update-description/{tube_id}/{commande_id}', [CommandController::class, 'updateDescription']);

Route::put('/rack/update/{id}',[administrationController::class,'updateRack']);