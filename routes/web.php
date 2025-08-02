<?php

use App\Http\Controllers\FootballMatchController;
use App\Http\Controllers\ScoreboardController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
Route::delete('/teams/{id}', [TeamController::class, 'destroy'])->name('teams.destroy');

Route::get('/matches', [FootballMatchController::class, 'index'])->name('matches.index');
Route::post('/matches', [FootballMatchController::class, 'store'])->name('matches.store');
Route::post('/matches/{match}/update', [FootballMatchController::class, 'update'])->name('matches.update');
Route::delete('/matches/{id}', [FootballMatchController::class, 'destroy']);
Route::post('/matches/{id}/update-score', [FootballMatchController::class, 'updateScore']);

Route::get('/scoreboard/{code}', [ScoreboardController::class, 'show'])->name('scoreboard.show');
Route::get('/api/scoreboard/{code}', [ScoreboardController::class, 'apiData'])->name('scoreboard.apiData');


Route::get('/{matchCode}/{stadium}/{tournament}/{broadcaster}/{status}/{teamA}/{teamB}/{colorA}/{colorB}/{timeParam}/{scoreA}/{scoreB}', [ScoreboardController::class, 'link'])
    ->name('scoreboard.link');
