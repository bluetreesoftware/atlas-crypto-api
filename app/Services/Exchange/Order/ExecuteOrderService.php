<?php

namespace App\Services\Exchange\Order;

use App\Models\Exchange\Order;
use App\Models\Exchange\Trade;

final class ExecuteOrderService
{
    /**
     * @param Trade $trade
     * @param Order $saleOrder
     * @param Order $buyOrder
     */
    public function __construct(
        private Trade $trade,
        private Order $saleOrder,
        private Order $buyOrder
    ) {}

    /**
     * @return float
     */
    public function getLotPrice(): float
    {
        return $this->saleOrder->price / $this->trade->baseCurrency->accuracy;
    }

    public function execute(): int
    {
        return 0;
    }
}
