<?php

namespace App\Enums;

enum MedicationFrequency: string
{
    case OnceDaily = 'once_daily';
    case TwiceDaily = 'twice_daily';
    case ThreeTimesDaily = 'three_times_daily';
    case EveryOtherDay = 'every_other_day';
    case Weekly = 'weekly';
    case AsNeeded = 'as_needed';

    public function label(): string
    {
        return match ($this) {
            self::OnceDaily => 'Once a day',
            self::TwiceDaily => 'Twice a day',
            self::ThreeTimesDaily => 'Three times a day',
            self::EveryOtherDay => 'Every other day',
            self::Weekly => 'Once a week',
            self::AsNeeded => 'As needed',
        };
    }
}
