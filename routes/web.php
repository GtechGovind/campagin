<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PosterController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'home']);
Route::post('/submit', [HomeController::class, 'submit'])->name('home.submit');
Route::get('/poster/{user_id}', [PosterController::class, 'home'])->name('poster');