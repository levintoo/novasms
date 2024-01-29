<?php

namespace App\Enums;

enum TransactionStatus: Int
{
 case PENDING = 0;
 case COMPLETED = 1;
 case CANCELLED = 3;
}
