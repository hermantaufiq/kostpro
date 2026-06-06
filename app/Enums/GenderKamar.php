<?php

namespace App\Enums;

enum GenderKamar: string
{
    case Putra  = 'putra';
    case Putri  = 'putri';
    case Campur = 'campur';

    public function label(): string
    {
        return match($this) {
            self::Putra  => 'Putra',
            self::Putri  => 'Putri',
            self::Campur => 'Campur',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Putra  => 'indigo',
            self::Putri  => 'rose',
            self::Campur => 'emerald',
        };
    }

    public function getLabel(): string
    {
        return $this->label();
    }
}
