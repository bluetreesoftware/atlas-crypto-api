<?php

namespace App\Enums\Exchange;

enum OrderStatusEnum: int
{
    case Open = 0;
    case Canceled = 1;
    case Completed = 2;
    case Processing = 3;
}
