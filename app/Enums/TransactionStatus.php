<?php

namespace App\Enums;

class TransactionStatus
{
    const PENDING = 0;
    const COMPLETED = 1;
    const FAILED = 2;
    const CANCELLED = 3;
    const REVERSED = 4;

    public static function getDescription($status)
    {
        switch ($status) {
            case self::PENDING:
                return 'Pending';
            case self::COMPLETED:
                return 'Completed';
            case self::FAILED:
                return 'Failed';
            case self::CANCELLED:
                return 'Cancelled';
            case self::REVERSED:
                return 'Reversed';
            default:
                return 'Unknown';
        }
    }
}
