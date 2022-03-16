<?php

namespace App\Services\Wallet;

use App\Models\Wallet;

final class VerificationBalanceForTransactionService
{
    public function __construct(
        private Wallet $wallet,
        private int $volume
    ) {}

    /**
     * @return bool
     */
    public function verify(): bool
    {
        $balanceWalletService = new GetWalletBalanceService(
            $this->wallet
        );

        return $balanceWalletService->getBalance() >= $this->volume;
    }
}
