<?php

namespace App\Services\Transaction\W2W;

use App\Enums\Transaction\TransactionStatusEnum;
use App\Models\Transaction;
use App\Services\Wallet\GetWalletBalanceService;
use App\Services\Wallet\VerificationBalanceForTransactionService;

final class ProcessingW2WTransactionService
{
    /**
     * @param Transaction $transaction
     */
    public function __construct(
        private Transaction $transaction
    ) {}

    /**
     * @return bool
     */
    private function isEnoughVolumeInWallet(): bool
    {
        return (new VerificationBalanceForTransactionService(
            $this->transaction->senderWallet,
            $this->transaction->volume
        ))->verify();
    }

    private function tryToAuthoriseTransaction()
    {
        if ($this->isEnoughVolumeInWallet()) {
            $this->transaction->update([
                'status' => TransactionStatusEnum::Authorised
            ]);
        } else {
            $this->transaction->update([
                'status' => TransactionStatusEnum::Failed
            ]);
        }
    }


    public function processing()
    {
        try {
            $this->tryToAuthoriseTransaction();
        } catch (\Throwable $exception) {
            $this->transaction->update([
                'status' => TransactionStatusEnum::Failed
            ]);
        }
    }
}
