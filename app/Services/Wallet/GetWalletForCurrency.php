<?php

namespace App\Services\Wallet;

use App\Models\Account;
use App\Models\Currency;
use App\Models\Wallet;

final class GetWalletForCurrency
{
    /**
     * @param Account $account
     * @param Currency $currency
     */
    public function __construct(
        private Account $account,
        private Currency $currency
    ) {}

    /**
     * @return Wallet
     * @throws \Exception
     */
    public function get(): Wallet
    {
        $wallet = $this->account->wallets()->forCurrency($this->currency)->first();

        if (! ($wallet instanceof Wallet)) {
            throw new \Exception('Wallet for this currency is not exists!');
        }

        return $wallet;
    }
}
