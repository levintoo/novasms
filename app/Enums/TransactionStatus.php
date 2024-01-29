<?php

namespace App\Enums;

enum TransactionStatus: Int
{
 case PENDING = 0;
 case COMPLETED = 1;
 case CANCELLED = 3;

    public static function getValueName(int $value): ?string
    {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return strtolower($case->name);
            }
        }
        return null;
    }
}
