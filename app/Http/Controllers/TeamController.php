<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::all();
        return view('teams.index', compact('teams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:teams,name|max:255',
            'code' => 'nullable|max:50',
            'logo' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('name', 'code');

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        Team::create($data);

        return redirect()->route('teams.index')->with('success', 'Team created successfully!');
    }

    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required|max:255|unique:teams,name,' . $team->id,
            'code' => 'nullable|max:50',
            'logo' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('name', 'code');

        if ($request->hasFile('logo')) {
            // Xóa logo cũ nếu có
            if ($team->logo_path && \Storage::disk('public')->exists($team->logo_path)) {
                \Storage::disk('public')->delete($team->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        $team->update($data);

        return redirect()->route('teams.index')->with('success', 'Team updated successfully!');
    }
    public function destroy($id)
    {
        $team = Team::findOrFail($id);

        // Xoá file logo nếu có
        if ($team->logo_path) {
            \Storage::delete($team->logo_path);
        }

        $team->delete();

        return redirect()->route('teams.index')->with('success', 'Team deleted successfully!');
    }
}
