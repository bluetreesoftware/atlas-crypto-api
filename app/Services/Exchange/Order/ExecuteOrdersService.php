<?php

namespace App\Services\Exchange\Order;

use App\Enums\Exchange\OrderActionEnum;
use App\Models\Exchange\Order;
use App\Models\Exchange\Trade;
use App\Services\Exchange\Order\Action\BuyLimitOrderService;
use App\Services\Exchange\Order\Action\SaleLimitOrderService;

final class ExecuteOrdersService
{
    /**
     * @param Trade $trade
     * @param Order $limitOrder
     * @param Order $marketOrder
     * @param OrderActionEnum $action
     */
    public function __construct(
        private Trade $trade,
        private Order $limitOrder,
        private Order $marketOrder,
        private OrderActionEnum $action
    ) {}

    /**
     * @return float
     */
    public function getLotPrice(): float
    {
        return $this->limitOrder->price / $this->trade->baseCurrency->accuracy;
    }

    /**
     * @return int
     */
    private function getAvailableQuantity(): int
    {
        return $this->limitOrder->actual_quantity >= $this->marketOrder->actual_quantity
            ? $this->marketOrder->actual_quantity
            : $this->limitOrder->actual_quantity;
    }

    /**
     * @return string
     */
    private function getActionClassName(): string
    {
        return $this->action === OrderActionEnum::Buy
            ? BuyLimitOrderService::class
            : SaleLimitOrderService::class;
    }

    public function execute(): void
    {
        try {

            $availableQuantity = $this->getAvailableQuantity();
            $totalPrice = $availableQuantity * $this->getLotPrice();

            $actionClassName = $this->getActionClassName();

            $action = new $actionClassName(
                $this->trade,
                $this->limitOrder,
                $this->marketOrder,
                $availableQuantity,
                $totalPrice
            );

            $action->execute();

        } catch (\Throwable $e) {

            dump($e->getMessage());
        }
    }
}
