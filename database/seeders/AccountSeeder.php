<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Wallet;
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
        Account::factory(100)->create()->each(function (Account $account) {
            $account->wallets()->saveMany(Wallet::factory(2)->make());
        });
    }
}
