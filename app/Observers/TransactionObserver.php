<?php

namespace App\Observers;

use App\Enums\Transaction\TransactionTypeEnum;
use App\Events\Transaction\W2W\OpenW2WTransactionEvent;
use App\Models\Transaction;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     *
     * @param Transaction  $transaction
     * @return void
     */
    public function created(Transaction $transaction)
    {
        switch ($transaction->type) {
            case TransactionTypeEnum::W2W:
                event(new OpenW2WTransactionEvent($transaction));
                break;
            case TransactionTypeEnum::P2P:
                //
                break;
            case TransactionTypeEnum::P2W:
                //
        }
    }

    /**
     * Handle the Transaction "updated" event.
     *
     * @param  Transaction  $transaction
     * @return void
     */
    public function updated(Transaction $transaction)
    {
        //
    }

    /**
     * Handle the Transaction "deleted" event.
     *
     * @param  Transaction  $transaction
     * @return void
     */
    public function deleted(Transaction $transaction)
    {
        //
    }

    /**
     * Handle the Transaction "restored" event.
     *
     * @param  Transaction  $transaction
     * @return void
     */
    public function restored(Transaction $transaction)
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     *
     * @param  Transaction  $transaction
     * @return void
     */
    public function forceDeleted(Transaction $transaction)
    {
        //
    }
}
