<?php

namespace App\Enums;

enum MatchStatus: int
{
    case SCHEDULED = 0;
    case LIVE = 1;
    case FINISHED = 99;
}
