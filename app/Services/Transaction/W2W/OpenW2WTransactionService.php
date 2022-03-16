<?php

namespace App\Services\Transaction\W2W;

use App\Enums\Transaction\TransactionStatusEnum;
use App\Enums\Transaction\TransactionTypeEnum;
use App\Events\Transaction\W2W\OpenW2WTransactionEvent;
use App\Models\Account;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\Currency\ConvertToSystemFormat;
use App\Services\Wallet\GetWalletForCurrency;

final class OpenW2WTransactionService
{
    /**
     * @var Wallet
     */
    private Wallet $senderWallet;

    /**
     * @var Wallet
     */
    private Wallet $recipientWallet;

    /**
     * @param Account $sender
     * @param Account $recipient
     * @param Currency $currency
     * @param float $volume
     * @throws \Exception
     */
    public function __construct(
        private Account $sender,
        private Account $recipient,
        private Currency $currency,
        private float $volume
    ) {
        $this->senderWallet = $this->getAccountWalletForCurrency($this->sender);
        $this->recipientWallet = $this->getAccountWalletForCurrency($this->recipient);
    }

    /**
     * @param Account $account
     * @return Wallet
     * @throws \Exception
     */
    private function getAccountWalletForCurrency(Account $account): Wallet
    {
        return (new GetWalletForCurrency($account, $this->currency))->get();
    }

    /**
     * @return Transaction
     */
    public function commit(): Transaction
    {
        return Transaction::create([
            'sender_wallet_id' => $this->senderWallet->id,
            'recipient_wallet_id' => $this->recipientWallet->id,
            'volume' => (new ConvertToSystemFormat($this->currency, $this->volume))->convert(),
            'status' => TransactionStatusEnum::Open,
            'type'=> TransactionTypeEnum::W2W
        ]);
    }
}
