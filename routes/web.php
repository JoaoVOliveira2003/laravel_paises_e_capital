<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;


// Route::get('/', function () {return view('home');});
Route::get('/', [MainController::class, 'telaIncial'])->name('telaIncial');
Route::post('/', [MainController::class, 'prepareGame'])->name('prepareGame');

//in game
Route::post('/game', [MainController::class, 'game'])->name('game');


