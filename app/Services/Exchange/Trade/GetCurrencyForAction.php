<?php

namespace App\Services\Exchange\Trade;

use App\Enums\Exchange\OrderActionEnum;
use App\Models\Currency;
use App\Models\Exchange\Trade;

final class GetCurrencyForAction
{
    /**
     * @param Trade $trade
     * @param OrderActionEnum $action
     */
    public function __construct(
        private Trade $trade,
        private OrderActionEnum $action
    ) {}

    public function get(): Currency
    {
        return $this->action === OrderActionEnum::Buy
            ? $this->trade->quoteCurrency
            : $this->trade->baseCurrency;
    }
}
