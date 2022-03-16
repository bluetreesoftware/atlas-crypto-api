<?php

namespace App\Services\Currency;

use App\Models\Currency;

final class ConvertToSystemFormat
{
    /**
     * @param Currency $currency
     * @param float $volume
     */
    public function __construct(
        private Currency $currency,
        private float $volume
    ) {}

    /**
     * @return int
     */
    public function convert(): int
    {
        return $this->volume * $this->currency->accuracy;
    }
}
