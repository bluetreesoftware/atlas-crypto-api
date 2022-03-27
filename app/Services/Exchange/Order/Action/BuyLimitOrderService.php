<?php

namespace App\Services\Exchange\Order\Action;

use App\Services\Transaction\O2W\CommitO2WTransaction;

final class BuyLimitOrderService extends AbstractLimitOrderService
{
    /**
     * @throws \Exception
     */
    protected function commitTransaction()
    {
        (new CommitO2WTransaction(
            $this->limitOrder,
            $this->limitAccount,
            $this->marketAccount,
            $this->quoteCurrency,
            $this->marketVolume
        ))->commit();

        (new CommitO2WTransaction(
            $this->marketOrder,
            $this->marketAccount,
            $this->limitAccount,
            $this->baseCurrency,
            $this->limitVolume
        ))->commit();
    }
}
