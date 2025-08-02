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
        $request->validate([
            'code' => 'required|unique:football_matches',
            'home_team_id' => 'required',
            'away_team_id' => 'required',
        ]);

        FootballMatch::create($request->only(['code', 'home_team_id', 'away_team_id', 'started_at']));

        return redirect()->route('matches.index');
    }

    public function edit($id)
    {
        $editMatch = FootballMatch::findOrFail($id);
        $matches = FootballMatch::with(['homeTeam', 'awayTeam'])->get();
        $teams = Team::all();

        return view('matches.index', compact('editMatch', 'matches', 'teams'));
    }

    public function update(Request $request, $id)
    {
        $match = FootballMatch::findOrFail($id);

        $request->validate([
            'code' => 'required|unique:football_matches,code,' . $id,
            'home_team_id' => 'required',
            'away_team_id' => 'required',
        ]);

        $match->update($request->only(['code', 'home_team_id', 'away_team_id', 'started_at']));

        return redirect()->route('matches.index');
    }

    public function destroy($id)
    {
        FootballMatch::findOrFail($id)->delete();
        return redirect()->route('matches.index');
    }
}
