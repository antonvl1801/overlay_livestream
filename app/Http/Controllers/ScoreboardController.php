<?php

namespace App\Http\Controllers;

use App\Enums\MatchStatus;
use App\Models\FootballMatch;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;

use function Symfony\Component\VarDumper\Dumper\esc;

class ScoreboardController extends Controller
{
    public function link($matchCode, $stadium, $tournament, $broadcaster, $status, $teamA, $teamB, $colorA, $colorB, $timeParam, $scoreA, $scoreB, $urlValueFlag)
    {
        if ($status == MatchStatus::SCHEDULED->value) {
            $teamA = Team::where('code', $teamA)->first() ?? $teamA;
            $teamB = Team::where('code', $teamB)->first() ?? $teamB;
        }

        if ($status == MatchStatus::LIVE->value) {
            $prefix = substr($timeParam, 0, 1);
            $value = (int) substr($timeParam, 1);
            $seconds = $prefix === 's' ? $value : $value * 60;
            if ($urlValueFlag !== 1) {
                $match = FootballMatch::where('code', $matchCode)
                    ->first();
                if ($match) {
                    $teamA = $match->homeTeam;
                    $teamB = $match->awayTeam;
                    $scoreA = $match->home_score;
                    $scoreB = $match->away_score;
                    $seconds = \Carbon\Carbon::parse($match->started_at)->diffInSeconds(\Carbon\Carbon::now());
                }
            }
        }

        if ($status == MatchStatus::FINISHED->value) {
            $match = FootballMatch::with([
                'homeTeam',
                'awayTeam',
                'goals.team'
            ])->where('code', $matchCode)->first();
            if ($match) {
                $teamA = $match->homeTeam;
                $teamB = $match->awayTeam;
                $scoreA = $match->home_score;
                $scoreB = $match->away_score;

                $homeTeamId = $match->home_team_id;
                $awayTeamId = $match->away_team_id;

                $homeGoals = [];
                $awayGoals = [];

                foreach ($match->goals as $goal) {
                    $goalData = [
                        'scorer_name' => $goal->scorer_name,
                        'minute' => $goal->minute,
                        'is_own_goal' => $goal->is_own_goal,
                        'team' => $goal->team->name,
                    ];

                    if ($goal->team_id === $homeTeamId) {
                        $homeGoals[] = $goalData;
                    } elseif ($goal->team_id === $awayTeamId) {
                        $awayGoals[] = $goalData;
                    }
                }
                $goals['home'] = $homeGoals;
                $goals['away'] = $awayGoals;
            } else {
                $teamA = Team::where('code', $teamA)->first() ?? $teamA;
                $teamB = Team::where('code', $teamB)->first() ?? $teamB;
                $goals = [];
            }
        }

        $seconds = $status == MatchStatus::LIVE->value ? $seconds : 0;
        $goals = $status == MatchStatus::FINISHED->value ? $goals : [];

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
            'scoreB',
            'goals',
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
