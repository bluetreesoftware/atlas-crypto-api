<?php

namespace App\Services\Exchange\Order;

use App\Enums\Exchange\OrderActionEnum;
use App\Enums\Exchange\OrderStatusEnum;
use App\Enums\Exchange\OrderTypeEnum;
use App\Models\Account;
use App\Models\Currency;
use App\Models\Exchange\Order;
use App\Models\Exchange\Trade;
use App\Services\Currency\ConvertToSystemFormat;
use App\Services\Exchange\Trade\GetCurrencyForAction;
use App\Services\Wallet\GetWalletForCurrency;
use App\Services\Wallet\VerificationBalanceForTransactionService;

final class OpenOrderService
{
    /**
     * @var Currency
     */
    private Currency $orderCurrency;

    /**
     * @param Account $account
     * @param Trade $trade
     * @param OrderTypeEnum $type
     * @param OrderActionEnum $action
     * @param int $price
     * @param int $quantity
     */
    public function __construct(
        private Account $account,
        private Trade $trade,
        private OrderTypeEnum $type,
        private OrderActionEnum $action,
        private int $price,
        private int $quantity,
    ) {

        $this->orderCurrency = (new GetCurrencyForAction($this->trade, $this->action))->get();
        dump($this->orderCurrency);
    }

    private function isAvailableCreateOrder(): bool
    {
        $wallet = (new GetWalletForCurrency($this->account, $this->orderCurrency))->get();
        $volume = $this->price * $this->quantity;

        return (new VerificationBalanceForTransactionService($wallet, $volume))->verify();
    }

    /**
     * @return int
     */
    private function getConvertedPrice(): int
    {
        return (new ConvertToSystemFormat($this->orderCurrency, $this->price))->convert();
    }

    /**
     * @return Order
     * @throws \Exception
     */
    public function open(): Order
    {
        if ($this->isAvailableCreateOrder()) {
            return Order::create([
                'account_id' => $this->account->id,
                'trade_id' => $this->trade->id,
                'type' => $this->type,
                'action' => $this->action,
                'price' => $this->getConvertedPrice(),
                'quantity' => $this->quantity,
                'status' => OrderStatusEnum::Open
            ]);
        } else {
            throw new \Exception('Not money');
        }
    }
}
