<?php

namespace Database\Seeders;

use App\Models\Exchange\Trade;
use Illuminate\Database\Seeder;

class TradeSeeder extends Seeder
{
    /**
     * @var array|array[]
     */
    protected array $trades = [
        [
            'code' => 'BTC_USDT',
            'base_currency_id' => 1,
            'quote_currency_id' => 2
        ],
        [
            'code' => 'ETH_USDT',
            'base_currency_id' => 3,
            'quote_currency_id' => 2
        ],
        [
            'code' => 'ETH_BTC',
            'base_currency_id' => 3,
            'quote_currency_id' => 1
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->trades as $trade) {
            Trade::create($trade);
        }
    }
}
