<?php

namespace App\Services\Exchange\Order\Action;

use App\Enums\Exchange\OrderStatusEnum;
use App\Models\Account;
use App\Models\Currency;
use App\Models\Exchange\Order;
use App\Models\Exchange\Trade;
use Illuminate\Support\Facades\DB;

abstract class AbstractLimitOrderService
{
    /**
     * @var Account
     */
    protected Account $limitAccount;

    /**
     * @var Account
     */
    protected Account $marketAccount;

    /**
     * @var Currency
     */
    protected Currency $baseCurrency;

    /**
     * @var Currency
     */
    protected Currency $quoteCurrency;

    /**
     * @param Trade $trade
     * @param Order $limitOrder
     * @param Order $marketOrder
     * @param int $limitVolume
     * @param int $marketVolume
     */
    public function __construct(
        protected Trade $trade,
        protected Order $limitOrder,
        protected Order $marketOrder,
        protected int $limitVolume,
        protected int $marketVolume
    ) {
        $this->limitAccount = $this->limitOrder->account;
        $this->marketAccount = $this->marketOrder->account;
        $this->baseCurrency = $this->trade->baseCurrency;
        $this->quoteCurrency = $this->trade->quoteCurrency;
    }

    abstract protected function commitTransaction();

    /**
     * @return bool
     */
    public function execute(): bool
    {
        try {
            $this->lockOrders();
            DB::beginTransaction();
            $this->commitTransaction();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            dump($e->getMessage());
            $this->unlockOrders();
            return false;
        }

        $this->validateOrderAfterCommitTransaction($this->limitOrder, $this->limitVolume);
        $this->validateOrderAfterCommitTransaction($this->marketOrder, $this->marketVolume);

        return true;
    }

    private function lockOrders()
    {
        $this->marketOrder->update([
            'status' => OrderStatusEnum::Processing
        ]);

        $this->limitOrder->update([
            'status' => OrderStatusEnum::Processing
        ]);
    }

    private function unlockOrders()
    {
        $this->marketOrder->update([
            'status' => OrderStatusEnum::Open
        ]);

        $this->limitOrder->update([
            'status' => OrderStatusEnum::Open
        ]);
    }

    /**
     * @param Order $order
     * @param int $quantity
     */
    private function validateOrderAfterCommitTransaction(Order $order, int $quantity)
    {
        if ($order->actual_quantity === $quantity) {
            $order->update([
                'status' => OrderStatusEnum::Completed,
                'actual_quantity' => 0
            ]);
        } elseif ($order->actual_quantity > $quantity) {
            $order->update([
                'status' => OrderStatusEnum::Open,
                'actual_quantity' => $order->actual_quantity - $quantity
            ]);
        }
    }
}
