<?php

namespace App\Enums;

enum TipeKamar: string
{
    case Standar = 'standar';
    case Deluxe  = 'deluxe';
    case VIP     = 'vip';
    case Suite   = 'suite';

    public function label(): string
    {
        return match($this) {
            self::Standar => 'Standar',
            self::Deluxe  => 'Deluxe',
            self::VIP     => 'VIP',
            self::Suite   => 'Suite',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Standar => 'slate',
            self::Deluxe  => 'indigo',
            self::VIP     => 'amber',
            self::Suite   => 'purple',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn($case) => ['value' => $case->value, 'label' => $case->label()],
            self::cases()
        );
    }
}
