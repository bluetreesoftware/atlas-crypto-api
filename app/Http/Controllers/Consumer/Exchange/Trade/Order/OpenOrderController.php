<?php

namespace App\Http\Controllers\Consumer\Exchange\Trade\Order;

use App\Enums\Exchange\OrderActionEnum;
use App\Enums\Exchange\OrderTypeEnum;
use App\Http\Controllers\Consumer\ConsumerController;
use App\Models\Exchange\Trade;
use App\Services\Exchange\Order\OpenOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OpenOrderController extends ConsumerController
{
    /**
     * @param int $tradeId
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function open(int $tradeId, Request $request): JsonResponse
    {
        $trade = Trade::find($tradeId);

        $order = (new OpenOrderService(
            $this->account,
            $trade,
            OrderTypeEnum::from($request->type),
            OrderActionEnum::from($request->action),
            $request->price,
            $request->quantity
        ))->open();

        return responder()->success($order)->respond(Response::HTTP_CREATED);
    }
}
