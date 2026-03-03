<?php

declare(strict_types=1);

namespace App\Enums;

enum MoodLevel: int
{
    case Terrible = 1;
    case Bad = 2;
    case Poor = 3;
    case BelowAverage = 4;
    case Average = 5;
    case AboveAverage = 6;
    case Good = 7;
    case Great = 8;
    case Excellent = 9;
    case Perfect = 10;
}
