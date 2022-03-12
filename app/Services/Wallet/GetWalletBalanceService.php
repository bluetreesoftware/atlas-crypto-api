<?php

namespace App\Services\Wallet;

use App\Models\Account;
use App\Models\Currency;
use App\Models\Wallet;

final class GetWalletBalanceService
{
    /**
     * @param Wallet $wallet
     */
    public function __construct(
        private Wallet $wallet
    ) {}

    /**
     * @return float
     */
    public function getBalance(): float
    {
        return $this->wallet->receivedTransactions()->authorised()->sum('volume') - $this->wallet->sentTransactions()->authorised()->sum('volume');
    }
}
