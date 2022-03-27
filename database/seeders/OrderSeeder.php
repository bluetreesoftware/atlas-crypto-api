<?php

namespace Database\Seeders;

use App\Enums\Exchange\OrderActionEnum;
use App\Enums\Exchange\OrderTypeEnum;
use App\Models\Account;
use App\Models\Currency;
use App\Models\Exchange\Order;
use App\Models\Exchange\Trade;
use App\Services\Currency\ConvertToSystemFormat;
use App\Services\Exchange\Order\OpenOrderService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $trade = Trade::first();

        Account::all()->each(function (Account $account) use ($trade) {
            DB::beginTransaction();
            for($i = 0; $i < 10; $i++) {
                $action = rand(0, 1)? OrderActionEnum::Buy : OrderActionEnum::Sale;

                if (rand(0, 1)) {
                    $type = OrderTypeEnum::Limit;
                    $price = rand(40000, 43000);
                } else {
                    $type = OrderTypeEnum::Market;
                    $price = 0;
                }

                (new OpenOrderService($account, $trade, $type, $action, $price, rand(1, 9)))->open();
            }
            DB::commit();
        });
    }
}
