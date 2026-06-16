<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [UserController::class, 'login'])->middleware('throttle:5,1');
Route::post('/user', [UserController::class, 'create'])->middleware('throttle:5,1');
Route::get('/user/{id}', [UserController::class, 'get'])->middleware('auth.jwt');
