<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function destroy(Goal $goal)
    {
        $match = $goal->match;
        $goal->delete();
        $match->recalculateScore();
        return back()->with('success', 'Xoá bàn thắng thành công!');
    }
}
