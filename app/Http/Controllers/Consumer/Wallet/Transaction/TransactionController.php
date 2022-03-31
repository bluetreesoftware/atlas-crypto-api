<?php

namespace App\Http\Controllers\Consumer\Wallet\Transaction;

use App\Http\Controllers\Consumer\ConsumerController;
use App\Models\Account;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\Transaction\W2W\OpenW2WTransactionService;
use App\Transformers\Consumer\Wallet\Transaction\TransactionTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

    /**
     * @param Wallet $wallet
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(Wallet $wallet, Request $request): JsonResponse
    {
        $recipient = Account::where('payment_id', $request->payment_id)->firstOrFail();
        $currency = Currency::find($request->currency_id);

        $transactionService = new OpenW2WTransactionService(
            $this->account,
            $recipient,
            $currency,
            $request->volume
        );

        return responder()->success($transactionService->commit())->respond(Response::HTTP_CREATED);
    }
}
