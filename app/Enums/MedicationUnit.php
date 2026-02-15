<?php

namespace App\Enums;

enum MedicationUnit: string
{
    case Tablet = 'tablet';
    case Drop = 'drop';
    case Capsule = 'capsule';
    case Ml = 'ml';
    case Mg = 'mg';
    case Injection = 'injection';
    case Spray = 'spray';
    case Patch = 'patch';

    public function label(): string
    {
        return match ($this) {
            self::Tablet => 'Tablet',
            self::Drop => 'Drop',
            self::Capsule => 'Capsule',
            self::Ml => 'Milliliter',
            self::Mg => 'Milligram',
            self::Injection => 'Injection',
            self::Spray => 'Spray',
            self::Patch => 'Patch',
        };
    }
}
