<?php

namespace App\Enums;

enum MatchStatus: int
{
    case SCHEDULED = 0;
    case LIVE = 1;
    case FINISHED = 99;

    public function label(): string
    {
        return match ($this) {
            self::SCHEDULED => 'Chưa diễn ra',
            self::LIVE => 'Đang diễn ra',
            self::FINISHED => 'Kết thúc',
        };
    }
}
