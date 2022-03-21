<?php

namespace App\Services\Transaction\O2W;

use App\Models\Exchange\Order;

final class CommitO2WTransaction
{
    /**
     * @param Order $buyOrder
     * @param Order $saleOrder
     */
    public function __construct(
        private Order $buyOrder,
        private Order $saleOrder
    ) {}

    public function commit()
    {

    }
}
