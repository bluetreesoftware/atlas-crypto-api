<?php

namespace App\Http\Controllers\Consumer\Exchange\Trade\Order;

use App\Enums\Exchange\OrderActionEnum;
use App\Enums\Exchange\OrderTypeEnum;
use App\Http\Controllers\Consumer\ConsumerController;
use App\Models\Exchange\Order;
use App\Models\Exchange\Trade;
use App\Services\Exchange\Order\OpenOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends ConsumerController
{
    /**
     * @param Trade $trade
     * @return JsonResponse
     */
    public function index(Trade $trade): JsonResponse
    {
        $orders = $this->account->orders()->forTrade($trade)->get();

        return responder()->success($orders)->respond();
    }

    /**
     * @param Request $request
     * @param Trade $trade
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(Request $request, Trade $trade): JsonResponse
    {
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

    /**
     * @param Order $order
     * @return JsonResponse
     */
    public function show(Order $order): JsonResponse
    {
        return responder()->success($order)->respond();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Exchange\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Exchange\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
