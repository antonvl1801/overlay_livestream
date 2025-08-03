<?php

namespace App\Http\Controllers;

use App\Enums\MatchStatus;
use App\Models\FootballMatch;
use App\Models\Team;
use Carbon\Carbon;

class ScoreboardController extends Controller
{
    public function link($matchCode, $status, $codeA, $codeB, $nameA, $nameB, $colorA, $colorB, $timeParam, $scoreA, $scoreB, $urlValueFlag)
    {
        if ($status == MatchStatus::SCHEDULED->value) {
            $teamA = Team::where('code', $codeA)->first() ?? $nameA;
            $teamB = Team::where('code', $codeB)->first() ?? $nameB;
        }

        if ($status == MatchStatus::LIVE->value) {
            $prefix = substr($timeParam, 0, 1);
            $value = (int) substr($timeParam, 1);
            $seconds = $prefix === 's' ? $value : $value * 60;
            $teamA = $nameA;
            $teamB = $nameB;
            if ($urlValueFlag != 1) {
                $match = FootballMatch::where('code', $matchCode)
                    ->first();
                if ($match) {
                    $teamA = $match->homeTeam;
                    $teamB = $match->awayTeam;
                    $scoreA = $match->home_score;
                    $scoreB = $match->away_score;
                    $seconds = max(0, \Carbon\Carbon::parse($match->started_at)->diffInSeconds(\Carbon\Carbon::now(), false));
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
                $teamA = Team::where('code', $codeA)->first() ?? $nameA;
                $teamB = Team::where('code', $codeB)->first() ?? $nameB;
                $goals = [];
            }
        }

        $seconds = $status == MatchStatus::LIVE->value ? $seconds : 0;
        $goals = $status == MatchStatus::FINISHED->value ? $goals : [];

        return view('scoreboard.link', compact(
            'matchCode',
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

    public function standings()
    {
        $teams = Team::with(['homeMatches' => function ($query) {
            $query->where('status', MatchStatus::FINISHED->value);
        }, 'awayMatches' => function ($query) {
            $query->where('status', MatchStatus::FINISHED->value);
        }])->get();

        $standings = $teams->map(function ($team) {
            $played = 0;
            $points = 0;
            $goalsFor = 0;
            $goalsAgainst = 0;

            // Tính cho các trận sân nhà
            foreach ($team->homeMatches as $match) {
                if ($match->home_score !== null && $match->away_score !== null) {
                    $played++;
                    $goalsFor += $match->home_score;
                    $goalsAgainst += $match->away_score;

                    if ($match->home_score > $match->away_score) {
                        $points += 3;
                    } elseif ($match->home_score == $match->away_score) {
                        $points += 1;
                    }
                }
            }

            // Tính cho các trận sân khách
            foreach ($team->awayMatches as $match) {
                if ($match->home_score !== null && $match->away_score !== null) {
                    $played++;
                    $goalsFor += $match->away_score;
                    $goalsAgainst += $match->home_score;

                    if ($match->away_score > $match->home_score) {
                        $points += 3;
                    } elseif ($match->away_score == $match->home_score) {
                        $points += 1;
                    }
                }
            }

            return [
                'team_name' => $team->name,
                'logo' => $team->logo_path,
                'points' => $points,
                'goal_difference' => $goalsFor - $goalsAgainst,
            ];
        });

        // Sắp xếp theo số điểm rồi đến hiệu số
        $sortedStandings = $standings->sortByDesc('points')
            ->sortByDesc('goal_difference')
            ->values();

        return view('standings.index', compact('sortedStandings'));
    }
}
