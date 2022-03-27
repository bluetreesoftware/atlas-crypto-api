<?php

namespace App\Services\Exchange\Order\Action;

use App\Services\Transaction\O2W\CommitO2WTransaction;

final class SaleLimitOrderService extends AbstractLimitOrderService
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
            $this->baseCurrency,
            $this->marketVolume
        ))->commit();

        (new CommitO2WTransaction(
            $this->marketOrder,
            $this->marketAccount,
            $this->limitAccount,
            $this->quoteCurrency,
            $this->limitVolume
        ))->commit();
    }
}
