<?php

namespace Tests\Feature\Consumer\Exchange\Trade\Order;

use App\Enums\Exchange\OrderActionEnum;
use App\Enums\Transaction\TransactionStatusEnum;
use App\Enums\Transaction\TransactionTypeEnum;
use App\Models\Account;
use App\Models\Transaction;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\TradeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class OpenOrderControllerTest extends TestCase
{

    use RefreshDatabase;

    protected Account $account;

    protected function setUp(): void
    {
        parent::setUp();

        $this->account = Account::factory()->create();

        $this->seed(CurrencySeeder::class);
        $this->seed(TradeSeeder::class);

        $this->account->wallets()->create([
            'currency_id' => 2,
            'external_id' => Str::uuid()
        ]);

        $this->account->wallets()->create([
            'currency_id' => 4,
            'external_id' => Str::uuid()
        ]);

        Transaction::create([
            'sender_wallet_id' => 0,
            'recipient_wallet_id' => 2,
            'volume' => 1,
            'status' => TransactionStatusEnum::Authorised,
            'type'=> TransactionTypeEnum::P2W
        ]);

        Transaction::create([
            'sender_wallet_id' => 0,
            'recipient_wallet_id' => $this->account->id,
            'volume' => 1,
            'status' => TransactionStatusEnum::Authorised,
            'type'=> TransactionTypeEnum::P2W
        ]);
    }

    public function test_account_can_create_order()
    {
        $response = $this->postJson(route('consumers.trades.orders', 1), [
            'quantity' => 10,
            'price' => 100,
            'action' => OrderActionEnum::Buy->value,
            'type' => 1
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }
}
