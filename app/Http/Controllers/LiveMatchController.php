<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use App\Models\Goal;
use Illuminate\Http\Request;

class LiveMatchController extends Controller
{
    public function show($code)
    {
        $match = FootballMatch::with(['homeTeam', 'awayTeam', 'goals'])->where('code', $code)->firstOrFail();

        $homeGoals = $match->goals->where('team_id', $match->home_team_id);
        $awayGoals = $match->goals->where('team_id', $match->away_team_id);

        return view('live.match', compact('match', 'homeGoals', 'awayGoals'));
    }

    public function storeGoal(Request $request, $code)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'scorer_name' => 'required|string',
            'minute' => 'required|integer',
            'is_own_goal' => 'boolean',
        ]);

        $match = FootballMatch::where('code', $code)->firstOrFail();

        Goal::create([
            'match_id' => $match->id,
            'team_id' => $request->team_id,
            'scorer_name' => $request->scorer_name,
            'minute' => $request->minute,
            'is_own_goal' => $request->is_own_goal ?? false,
        ]);

        $match->recalculateScore();

        return back()->with('success', 'Goal added!');
    }

    public function updateStatus(Request $request, $code)
    {
        $match = FootballMatch::where('code', $code)->firstOrFail();
        $match->update([
            'status' => (int) $request->status,
        ]);

        // Broadcast Pusher event ở đây nếu cần

        return back()->with('success', 'Status updated!');
    }
}

