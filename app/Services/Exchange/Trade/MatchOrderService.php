<?php

namespace App\Services\Exchange\Trade;

use App\Enums\Exchange\OrderActionEnum;
use App\Models\Exchange\Trade;
use App\Services\Exchange\Order\ExecuteOrdersService;

final class MatchOrderService
{
    /**
     * @param Trade $trade
     * @param OrderActionEnum $limitAction
     * @param OrderActionEnum $marketAction
     * @throws \Exception
     */
    public function __construct(
        private Trade $trade,
        private OrderActionEnum $limitAction,
        private OrderActionEnum $marketAction
    ) {
        if ($this->limitAction === $this->marketAction) {
            throw new \Exception('Invalid actions!');
        }
    }

    /**
     * @return bool
     */
    public function match(): bool
    {
        $limitOrder = (new GetExecutableLimitOrderService($this->trade, $this->limitAction))->get();

        if ($limitOrder) {
            $marketOrder = (new GetExecutableMarketOrderService($this->trade, $this->marketAction))->get();

            if ($marketOrder) {
                (new ExecuteOrdersService($this->trade, $limitOrder, $marketOrder, $this->limitAction))->execute();

                return true;
            }
        }

        return false;
    }
}
