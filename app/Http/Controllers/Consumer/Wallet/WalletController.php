<?php

namespace App\Http\Controllers\Consumer\Wallet;

use App\Http\Controllers\Consumer\ConsumerController;
use App\Http\Requests\Consumer\Wallet\StoreRequest;
use App\Models\Currency;
use App\Models\Wallet;
use App\Services\Wallet\CreateWalletService;
use App\Transformers\Consumer\Wallet\WalletTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class WalletController extends ConsumerController
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return responder()->success($this->account->wallets, WalletTransformer::class)->respond();
    }

    /**
     * @param StoreRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $createWalletService = new CreateWalletService(
            $this->account,
            Currency::find($request->currency_id)
        );

        $createWalletService->create();

        return responder()->success()->respond(Response::HTTP_CREATED);
    }

    /**
     * @param Wallet $wallet
     * @return JsonResponse
     */
    public function show(Wallet $wallet): JsonResponse
    {
        return responder()->success($wallet, WalletTransformer::class)->respond();
    }
}
