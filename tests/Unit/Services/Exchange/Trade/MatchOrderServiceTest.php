<?php

namespace Tests\Unit\Services\Exchange\Trade;

use App\Enums\Exchange\OrderActionEnum;
use App\Enums\Exchange\OrderTypeEnum;
use App\Enums\Transaction\TransactionStatusEnum;
use App\Enums\Transaction\TransactionTypeEnum;
use App\Models\Account;
use App\Models\Exchange\Trade;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\Exchange\Order\OpenOrderService;
use App\Services\Exchange\Trade\MatchOrderService;
use App\Services\Wallet\GetWalletBalanceService;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\TradeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatchOrderServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Account
     */
    protected Account $limitAccount;

    /**
     * @var Account
     */
    protected Account $marketAccount;

    /**
     * @var Wallet
     */
    protected Wallet $limitAccountBTCWallet;

    /**
     * @var Wallet
     */
    protected Wallet $limitAccountUSDTWallet;

    /**
     * @var Wallet
     */
    protected Wallet $marketAccountBTCWallet;

    /**
     * @var Wallet
     */
    protected Wallet $marketAccountUSDTWallet;

    /**
     * @var Trade
     */
    protected Trade $trade;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TradeSeeder::class);
        $this->seed(CurrencySeeder::class);

        /** @var Trade trade */
        $this->trade = Trade::first();

        $this->limitAccount = Account::factory()->createOne();
        $this->marketAccount = Account::factory()->createOne();

        $this->limitAccountBTCWallet = Wallet::factory()->create([
            'account_id' => $this->limitAccount->id,
            'currency_id' => $this->trade->baseCurrency
        ]);

        $this->limitAccountUSDTWallet = Wallet::factory()->create([
            'account_id' => $this->limitAccount->id,
            'currency_id' => $this->trade->quoteCurrency
        ]);

        $this->marketAccountBTCWallet = Wallet::factory()->create([
            'account_id' => $this->marketAccount->id,
            'currency_id' => $this->trade->baseCurrency
        ]);

        $this->marketAccountUSDTWallet = Wallet::factory()->create([
            'account_id' => $this->marketAccount->id,
            'currency_id' => $this->trade->quoteCurrency
        ]);

        Transaction::create([
            'sender_wallet_id' => 0,
            'recipient_wallet_id' => $this->limitAccountBTCWallet->id,
            'volume' => 900000000, // 9 BTC
            'status' => TransactionStatusEnum::Authorised,
            'type'=> TransactionTypeEnum::P2W
        ]);

        Transaction::create([
            'sender_wallet_id' => 0,
            'recipient_wallet_id' =>  $this->marketAccountBTCWallet->id,
            'volume' => 900000000, // 9 BTC
            'status' => TransactionStatusEnum::Authorised,
            'type'=> TransactionTypeEnum::P2W
        ]);

        Transaction::create([
            'sender_wallet_id' => 0,
            'recipient_wallet_id' => $this->limitAccountUSDTWallet->id,
            'volume' => 50000000, // 500k USDT
            'status' => TransactionStatusEnum::Authorised,
            'type'=> TransactionTypeEnum::P2W
        ]);

        Transaction::create([
            'sender_wallet_id' => 0,
            'recipient_wallet_id' =>  $this->marketAccountUSDTWallet->id,
            'volume' => 50000000, // 500K USDT
            'status' => TransactionStatusEnum::Authorised,
            'type'=> TransactionTypeEnum::P2W
        ]);
    }

    public function test_sale_limit_and_buy_market_equals_volume()
    {
        (new OpenOrderService(
            $this->limitAccount,
            $this->trade,
            OrderTypeEnum::Limit,
            OrderActionEnum::Sale,
            50000,
            5
        ))->open();

        (new OpenOrderService(
            $this->marketAccount,
            $this->trade,
            OrderTypeEnum::Market,
            OrderActionEnum::Buy,
            0,
            5
        ))->open();

        (new MatchOrderService($this->trade, OrderActionEnum::Sale, OrderActionEnum::Buy))->match();

        $limitAccountUSDTBalance = (new GetWalletBalanceService($this->limitAccountUSDTWallet))->getBalance();
        $limitAccountBTCBalance = (new GetWalletBalanceService($this->limitAccountBTCWallet))->getBalance();

        $marketAccountUSDTBalance = (new GetWalletBalanceService($this->marketAccountUSDTWallet))->getBalance();
        $marketAccountBTCBalance = (new GetWalletBalanceService($this->marketAccountBTCWallet))->getBalance();

        $this->assertEquals(75000000, $limitAccountUSDTBalance);
        $this->assertEquals(400000000, $limitAccountBTCBalance);

        $this->assertEquals(25000000, $marketAccountUSDTBalance);
        $this->assertEquals(1400000000, $marketAccountBTCBalance);
    }
}
