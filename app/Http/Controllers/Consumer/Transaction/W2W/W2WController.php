<?php

namespace App\Http\Controllers\Consumer\Transaction\W2W;

use App\Http\Controllers\Consumer\ConsumerController;
use App\Http\Requests\Consumer\Transaction\W2W\CreateRequest;
use App\Models\Account;
use App\Models\Currency;
use App\Services\Transaction\W2W\OpenW2WTransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class W2WController extends ConsumerController
{
    /**
     * @param CreateRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function __invoke(CreateRequest $request): JsonResponse
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
