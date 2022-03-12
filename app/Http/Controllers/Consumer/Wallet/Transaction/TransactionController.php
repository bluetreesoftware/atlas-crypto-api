<?php

namespace App\Http\Controllers\Consumer\Wallet\Transaction;

use App\Http\Controllers\Consumer\ConsumerController;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Transformers\Consumer\Wallet\Transaction\TransactionTransformer;
use Illuminate\Http\JsonResponse;

class TransactionController extends ConsumerController
{
    /**
     * @param Wallet $wallet
     * @return JsonResponse
     */
    public function index(Wallet $wallet): JsonResponse
    {
        return responder()->success($wallet->transactions, TransactionTransformer::class)->respond();
    }

    /**
     * @param Wallet $wallet
     * @param Transaction $transaction
     * @return JsonResponse
     */
    public function show(Wallet $wallet, Transaction $transaction): JsonResponse
    {
        return responder()->success($transaction, TransactionTransformer::class)->respond();
    }
}
