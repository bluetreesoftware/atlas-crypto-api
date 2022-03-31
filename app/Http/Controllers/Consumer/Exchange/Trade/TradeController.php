<?php

namespace App\Http\Controllers\Consumer\Exchange\Trade;

use App\Http\Controllers\Controller;
use App\Models\Exchange\Trade;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TradeController
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return responder()->success(Trade::all())->respond();
    }

    /**
     * @param Trade $trade
     * @return JsonResponse
     */
    public function show(Trade $trade): JsonResponse
    {
        return responder()->success($trade)->respond();
    }
}
