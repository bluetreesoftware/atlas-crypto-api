<?php

namespace Tests\Feature\Consumer\Exchange\Trade\Order;

use App\Enums\Exchange\OrderActionEnum;
use App\Enums\Transaction\TransactionStatusEnum;
use App\Enums\Transaction\TransactionTypeEnum;
use App\Models\Account;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\Currency\ConvertToSystemFormat;
use App\Services\Wallet\GetWalletBalanceService;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\TradeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class OpenOrderControllerTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @var Account
     */
    protected Account $account;

    /**
     * @var Wallet
     */
    protected Wallet $walletBTC;

    /**
     * @var Wallet
     */
    protected Wallet $walletUSDT;

    protected function setUp(): void
    {
        parent::setUp();

        $this->account = Account::factory()->create();
        $this->actingAs($this->account);
        $this->seed(CurrencySeeder::class);
        $this->seed(TradeSeeder::class);

        $this->walletBTC = $this->account->wallets()->create([
            'currency_id' => 1,
            'external_id' => Str::uuid()
        ]);

        $this->walletUSDT = $this->account->wallets()->create([
            'currency_id' => 2,
            'external_id' => Str::uuid()
        ]);

        Transaction::create([
            'sender_wallet_id' => 0,
            'recipient_wallet_id' => 2,
            'volume' => 50000,
            'status' => TransactionStatusEnum::Authorised,
            'type'=> TransactionTypeEnum::P2W
        ]);
    }

    public function test_account_can_create_order()
    {
        $response = $this->postJson(route('consumers.trades.orders', 1), [
            'quantity' => 1,
            'price' => 41000,
            'action' => OrderActionEnum::Buy->value,
            'type' => 1
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }
}
