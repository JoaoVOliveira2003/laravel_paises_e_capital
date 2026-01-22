<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;


Route::get('/home', function () {
    return view('home');
})->name('home');


Route::get('/', [MainController::class, 'telaIncial'])->name('telaIncial');
Route::post('/', [MainController::class, 'prepareGame'])->name('prepareGame');

//in game
// Route::post('/game', [MainController::class, 'game'])->name('game');

Route::match(['get','post'], '/game', [MainController::class, 'game'])->name('game');

Route::get('/aswer/{aswer}', [MainController::class, 'aswer'])->name('aswer');
Route::get('/next_question', [MainController::class, 'next_question'])->name('next_question');
Route::get('/showResults', [MainController::class, 'showResults'])->name('showResults');

