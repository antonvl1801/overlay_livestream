<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use App\Models\Team;
use Illuminate\Http\Request;

class FootballMatchController extends Controller
{
    public function index()
    {
        $matches = FootballMatch::with(['homeTeam', 'awayTeam'])->get();
        $teams = Team::all();
        return view('matches.index', compact('matches', 'teams'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:football_matches',
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'start_time' => 'required|date',
            'status' => 'required|in:scheduled,live,finished',
        ]);

        FootballMatch::create($validated);
        return redirect()->route('matches.index')->with('success', 'Match added.');
    }

    public function update(Request $request, FootballMatch $match)
    {
        $validated = $request->validate([
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'start_time' => 'required|date',
            'status' => 'required|in:scheduled,live,finished',
        ]);

        $match->update($validated);
        return redirect()->route('matches.index')->with('success', 'Match updated.');
    }

    public function destroy($id)
    {
        $match = FootballMatch::findOrFail($id);
        $match->delete();
        return response()->json(['success' => true]);
    }

    public function updateScore(Request $request, $id)
    {
        $match = FootballMatch::findOrFail($id);
        if ($request->team === 'home') {
            $match->home_score = (int)$request->score;
        } else {
            $match->away_score = (int)$request->score;
        }
        $match->save();

        return response()->json(['success' => true]);
    }
}
