<?php

namespace App\Transformers\Consumer\Wallet;

use App\Models\Currency;
use App\Models\Wallet;
use App\Services\Currency\ConvertToConsumerFormat;
use App\Services\Wallet\GetWalletBalanceService;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class WalletTransformer extends TransformerAbstract
{
    /**
     * @var string[]
     */
    protected $defaultIncludes = [
        'currency'
    ];

    /**
     * @param Wallet $wallet
     * @return array
     */
    public function transform(Wallet $wallet): array
    {
        $rawBalance = (new GetWalletBalanceService($wallet))->getBalance();

        return [
            'id' => $wallet->id,
            'external_id' => $wallet->external_id,
            'balance' => (new ConvertToConsumerFormat($wallet->currency, $rawBalance))->convert(),
        ];
    }

    /**
     * @param Wallet $wallet
     * @return Item
     */
    public function includeCurrency(Wallet $wallet): Item
    {
        return $this->item($wallet->currency, function (Currency $currency) {
            return [
                'id' => $currency->id,
                'name' => $currency->name,
                'ticker' => $currency->ticker
            ];
        });
    }
}
