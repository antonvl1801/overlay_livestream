<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FootballMatch extends Model
{
    protected $fillable = [
        'code', 'home_team_id', 'away_team_id', 'start_time', 'home_score', 'away_score', 'status'
    ];

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }
}
