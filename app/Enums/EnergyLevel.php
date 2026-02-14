<?php

namespace App\Enums;

enum EnergyLevel: int
{
    case Exhausted = 1;
    case VeryLow = 2;
    case Low = 3;
    case BelowAverage = 4;
    case Average = 5;
    case AboveAverage = 6;
    case High = 7;
    case VeryHigh = 8;
    case Energetic = 9;
    case Supercharged = 10;
}
