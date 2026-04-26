<?php

namespace App\Enums;

enum BusinessType: string
{
    case Llc = 'llc';
    case Corporation = 'corporation';
    case SoleProprietorship = 'sole_proprietorship';
    case Partnership = 'partnership';
    case Nonprofit = 'nonprofit';

    public function label(): string
    {
        return match ($this) {
            self::Llc => 'LLC',
            self::Corporation => 'Corporation',
            self::SoleProprietorship => 'Sole Proprietorship',
            self::Partnership => 'Partnership',
            self::Nonprofit => 'Nonprofit',
        };
    }
}
