<?php

namespace App\Services\Exchange\Trade;

use App\Enums\Exchange\OrderActionEnum;
use App\Models\Exchange\Order;
use App\Models\Exchange\Trade;

final class GetExecutableLimitOrderService
{
    /**
     * @param Trade $trade
     * @param OrderActionEnum $action
     */
    public function __construct(
        private Trade $trade,
        private OrderActionEnum $action
    ) {}

    /**
     * @return Order|null
     */
    public function get(): ?Order
    {
        return Order::query()
            ->limitType()
            ->open()
            ->where('trade_id', $this->trade->id)
            ->where('action', $this->action->value)
            ->orderBy('price', 'desc')
            ->orderBy('quantity', 'desc')
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
