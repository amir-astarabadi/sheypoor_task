<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/users', [UserController::class, 'index'])->name('users.index'); 
Route::post('/users', [UserController::class, 'store'])->name('users.store');    
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');    
