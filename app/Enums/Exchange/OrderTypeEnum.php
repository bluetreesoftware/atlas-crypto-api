<?php

namespace App\Enums\Exchange;

enum OrderTypeEnum: int
{
    case Market = 0;
    case Limit = 1;
}
