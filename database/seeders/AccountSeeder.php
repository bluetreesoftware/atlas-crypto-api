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
use Illuminate\Support\Facades\DB;


class AccountSeeder extends Seeder
{
    /**
     * @var array|\string[][]
     */
    protected array $accounts = [
        [
            'email' => 'denis.budancev@gmail.com',
            'first_name' => 'Denis',
            'last_name' => 'Budancev'
        ],
        [
            'email' => 'godunovofc@gmail.com',
            'first_name' => 'Konstantin',
            'last_name' => 'Godunov'
        ],
        [
            'email' => 'obval.activov@gmail.com',
            'first_name' => 'Obval',
            'last_name' => 'Activov'
        ],
        [
            'email' => 'ucet.rashodov@gmail.com',
            'first_name' => 'Ucet',
            'last_name' => 'Rashodov'
        ],
        [
            'email' => 'podriv.ustoev@gmail.com',
            'first_name' => 'Podriv',
            'last_name' => 'Ustoev'
        ],
        [
            'email' => 'rulon.oboev@gmail.com',
            'first_name' => 'Rulon',
            'last_name' => 'Oboev'
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencyBTC= Currency::find(1);

        foreach ($this->accounts as $account) {
            Account::factory(1)->createOne($account);
        }

        Account::factory(100)->create();

        Account::all()->each(function (Account $account) use ($currencyBTC) {
            DB::beginTransaction();
            $walletBTC = $account->wallets()->save(Wallet::factory()->make(['currency_id' => 1]));
            $walletUSDT = $account->wallets()->save(Wallet::factory()->make(['currency_id' => 2]));
            DB::commit();

            DB::beginTransaction();
            Transaction::create([
                'sender_wallet_id' => 0,
                'recipient_wallet_id' => $walletBTC->id,
                'volume' => (new ConvertToSystemFormat($currencyBTC, 100))->convert(),
                'type' => TransactionTypeEnum::P2W,
                'status' => TransactionStatusEnum::Authorised
            ]);

            Transaction::create([
                'sender_wallet_id' => 0,
                'recipient_wallet_id' => $walletUSDT->id,
                'volume' => 1000000,
                'type' => TransactionTypeEnum::P2W,
                'status' => TransactionStatusEnum::Authorised
            ]);
            DB::commit();
        });

    }
}
