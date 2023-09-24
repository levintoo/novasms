<?php

namespace App\Enums;

class TransactionType
{
    const CREDIT = 1;
    const DEBIT = 0;

    public static function getDescription($status)
    {
        switch ($status) {
            case self::CREDIT:
                return 'Credit';
            case self::DEBIT:
                return 'Debit';
            default:
                return 'Unknown';
        }
    }
}
