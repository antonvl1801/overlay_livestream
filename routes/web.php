<?php

use App\Http\Controllers\FootballMatchController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\LiveMatchController;
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

Route::resource('matches', FootballMatchController::class)->except(['show']);

Route::get('/scoreboard/{code}', [ScoreboardController::class, 'show'])->name('scoreboard.show');
Route::get('/api/scoreboard/{code}', [ScoreboardController::class, 'apiData'])->name('scoreboard.apiData');


Route::get('/{matchCode}/{status}/{teamA}/{teamB}/{colorA}/{colorB}/{timeParam}/{scoreA}/{scoreB}/{urlValueFlag}', [ScoreboardController::class, 'link'])
    ->name('scoreboard.link');

Route::get('/live/{code}', [LiveMatchController::class, 'show'])->name('live.match');
Route::post('/live/{code}/goal', [LiveMatchController::class, 'storeGoal'])->name('live.match.goal');
Route::post('/live/{code}/status', [LiveMatchController::class, 'updateStatus'])->name('live.match.status');

Route::delete('/goals/{goal}', [GoalController::class, 'destroy'])->name('goals.destroy');


