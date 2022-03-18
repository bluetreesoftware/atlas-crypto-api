<?php

namespace Database\Seeders;

use App\Enums\Transaction\TransactionStatusEnum;
use App\Enums\Transaction\TransactionTypeEnum;
use App\Models\Account;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\Currency\ConvertToSystemFormat;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currency = Currency::first();
        Account::factory(100)->create()->each(function (Account $account) use ($currency) {
            $wallet = $account->wallets()->save(Wallet::factory()->make(['currency_id' => 1]));

            Transaction::create([
                'sender_wallet_id' => 0,
                'recipient_wallet_id' => $wallet->id,
                'volume' => (new ConvertToSystemFormat($currency, 100))->convert(),
                'type' => TransactionTypeEnum::P2W,
                'status' => TransactionStatusEnum::Authorised
            ]);
        });
    }
}
