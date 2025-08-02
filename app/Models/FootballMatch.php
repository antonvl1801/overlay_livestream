<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FootballMatch extends Model
{
    protected $fillable = [
        'code',
        'home_team_id',
        'away_team_id',
        'started_at',
        'home_score',
        'away_score',
        'status'
    ];

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function goals()
    {
        return $this->hasMany(Goal::class, 'match_id');
    }

    public function recalculateScore(): void
    {
        $homeTeamId = $this->home_team_id;
        $awayTeamId = $this->away_team_id;
        $matchId = $this->id;

        $homeGoals = Goal::where('match_id', $matchId)
            ->where('team_id', $homeTeamId)
            ->where('is_own_goal', false)
            ->count();

        $awayGoals = Goal::where('match_id', $matchId)
            ->where('team_id', $awayTeamId)
            ->where('is_own_goal', false)
            ->count();

        $homeOwnGoals = Goal::where('match_id', $matchId)
            ->where('team_id', $homeTeamId)
            ->where('is_own_goal', true)
            ->count();

        $awayOwnGoals = Goal::where('match_id', $matchId)
            ->where('team_id', $awayTeamId)
            ->where('is_own_goal', true)
            ->count();

        $this->home_score = $homeGoals + $awayOwnGoals;
        $this->away_score = $awayGoals + $homeOwnGoals;
        $this->save();

        // Tuỳ chọn: broadcast cập nhật tỉ số ở đây
        // event(new MatchUpdated($this));
    }
}
