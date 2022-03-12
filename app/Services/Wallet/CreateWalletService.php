<?php

namespace App\Services\Wallet;

use App\Models\Account;
use App\Models\Currency;
use Illuminate\Support\Str;

final class CreateWalletService
{
    public function __construct(
        private Account $account,
        private Currency $currency
    ) {}

    /**
     * @return bool
     */
    private function isExistWallet(): bool
    {
        return $this->account->wallets()
                            ->forCurrency($this->currency)
                            ->exists();
    }

    /**
     * @throws \Exception
     */
    public function create()
    {
        if (!$this->isExistWallet()) {
            $this->account->wallets()->create([
                'external_id' => Str::uuid(),
                'currency_id' => $this->currency->id
            ]);
        } else {
            throw new \Exception('Wallet already exists!');
        }
    }
}
