<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScoreboardController extends Controller
{
    public function link($matchCode, $stadium, $tournament, $broadcaster, $status, $teamA, $teamB, $colorA, $colorB, $timeParam, $scoreA, $scoreB)
    {
        $prefix = substr($timeParam, 0, 1);
        $value = (int) substr($timeParam, 1);
        $seconds = $prefix === 's' ? $value : $value * 60;

        $teamA = Team::where('code', $teamA)->first() ?? $teamA;
        $teamB = Team::where('code', $teamB)->first() ?? $teamB;
        $scoreA = $scoreA === 'null' ? 0 : (int) $scoreA;
        $scoreB = $scoreB === 'null' ? 0 : (int) $scoreB;

        return view('scoreboard.link', compact(
            'matchCode',
            'stadium',
            'tournament',
            'broadcaster',
            'status',
            'teamA',
            'teamB',
            'colorA',
            'colorB',
            'seconds',
            'scoreA',
            'scoreB'
        ));
    }

    public function show($code)
    {
        $match = FootballMatch::with(['homeTeam', 'awayTeam'])
            ->where('code', $code)
            ->firstOrFail();

        $match->start_time = Carbon::parse($match->start_time);

        return view('scoreboard.index', compact('match'));
    }

    public function apiData($code)
    {
        $match = FootballMatch::with(['homeTeam', 'awayTeam'])
            ->where('code', $code)
            ->firstOrFail();

        return response()->json([
            'home_team' => $match->homeTeam->name,
            'away_team' => $match->awayTeam->name,
            'home_score' => $match->home_score,
            'away_score' => $match->away_score,
            'start_time' => $match->start_time->toDateTimeString(),
            'status' => $match->status,
        ]);
    }
}
