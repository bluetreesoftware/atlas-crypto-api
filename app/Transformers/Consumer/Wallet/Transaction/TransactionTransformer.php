<?php

namespace App\Transformers\Consumer\Wallet\Transaction;

use App\Models\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * @param Transaction $transaction
     * @return array
     */
    public function transform(Transaction $transaction): array
    {
        return [
            'id' => $transaction->id,
            'volume' => $transaction->volume,
            'status' => $transaction->status,
            'created_at' => $transaction->created_at->format('d.m.Y H:i:s')
        ];
    }
}
