<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/games/start', [\App\Http\Controllers\Api\GamePlayController::class, 'start'])->name('game.play.start');
    Route::post('/games/finish', [\App\Http\Controllers\Api\GamePlayController::class, 'finish'])->name('game.play.finish');
});

Route::get('/export', [\App\Http\Controllers\Api\ExportController::class, 'index'])->name('game.export');
