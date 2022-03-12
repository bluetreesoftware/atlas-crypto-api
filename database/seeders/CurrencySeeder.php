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
            'name' => 'Rubles',
            'ticker' => 'CRUB'
        ],
        [
            'name' => 'Bitcoin',
            'ticker' => 'BTC'
        ],
        [
            'name' => 'Ethereum',
            'ticker' => 'ETH'
        ],
        [
            'name' => 'Tether',
            'ticker' => 'USDT'
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
