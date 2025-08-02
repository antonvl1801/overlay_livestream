<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'match_id',
        'team_id',
        'scorer_name',
        'minute',
        'is_own_goal',
    ];
    
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function match()
    {
        return $this->belongsTo(FootballMatch::class, 'match_id');
    }
}
