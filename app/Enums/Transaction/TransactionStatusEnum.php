<?php

namespace App\Enums\Transaction;

enum TransactionStatusEnum: int
{
    case Open = 0;
    case Pending = 1;
    case Failed = 2;
    case Canceled = 3;
    case Authorised = 4;
}
