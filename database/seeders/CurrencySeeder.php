<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * @var \string[][]
     */
    protected $currencies = [
        [
            'name' => 'Bitcoin',
            'ticker' => 'BTC',
            'accuracy' => 100000000
        ],
        [
            'name' => 'Rubles',
            'ticker' => 'CRUB',
            'accuracy' => 1
        ],
        [
            'name' => 'Ethereum',
            'ticker' => 'ETH',
            'accuracy' => 10000
        ],
        [
            'name' => 'Tether',
            'ticker' => 'USDT',
            'accuracy' => 1
        ]
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->currencies as $currency) {
            Currency::create($currency);
        }
    }
}
