<?php

namespace App\Enums;

enum MetodePembayaran: string
{
    case VirtualAccount  = 'virtual_account';
    case QRIS            = 'qris';
    case EWallet         = 'ewallet';
    case CreditCard      = 'credit_card';
    case Retail          = 'retail';

    public function label(): string
    {
        return match($this) {
            self::VirtualAccount => 'Virtual Account',
            self::QRIS           => 'QRIS',
            self::EWallet        => 'E-Wallet',
            self::CreditCard     => 'Kartu Kredit',
            self::Retail         => 'Gerai Retail',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::VirtualAccount => '🏦',
            self::QRIS           => '📱',
            self::EWallet        => '💳',
            self::CreditCard     => '💳',
            self::Retail         => '🏪',
        };
    }
}
