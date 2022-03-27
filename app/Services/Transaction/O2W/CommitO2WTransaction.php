<?php

namespace App\Services\Transaction\O2W;

use App\Enums\Transaction\TransactionStatusEnum;
use App\Enums\Transaction\TransactionTypeEnum;
use App\Models\Account;
use App\Models\Currency;
use App\Models\Exchange\Order;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\Wallet\GetWalletForCurrency;
use Illuminate\Database\Eloquent\Model;

final class CommitO2WTransaction
{
    /**
     * @param Order $order
     * @param Account $sender
     * @param Account $recipient
     * @param Currency $currency
     * @param int $volume
     */
    public function __construct(
        private Order $order,
        private Account $sender,
        private Account $recipient,
        private Currency $currency,
        private int $volume
    ) {}

    /**
     * @return Wallet
     * @throws \Exception
     */
    private function getRecipientWallet(): Wallet
    {
        return (new GetWalletForCurrency($this->recipient, $this->currency))->get();
    }

    /**
     * @return Wallet
     * @throws \Exception
     */
    private function getSenderWallet(): Wallet
    {
        return (new GetWalletForCurrency($this->sender, $this->currency))->get();
    }

    /**
     * @return Model|Transaction
     * @throws \Exception
     */
    public function commit(): Model | Transaction
    {
        $senderWallet = $this->getSenderWallet();
        $recipientWallet = $this->getRecipientWallet();

        return $this->order->transactions()->create([
            'sender_wallet_id' => $senderWallet->id,
            'recipient_wallet_id' => $recipientWallet->id,
            'volume' => $this->volume,
            'status' => TransactionStatusEnum::Authorised,
            'type' => TransactionTypeEnum::O2W
        ]);
    }
}
