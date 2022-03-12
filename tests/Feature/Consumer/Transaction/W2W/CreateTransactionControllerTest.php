<?php

namespace Tests\Feature\Consumer\Transaction\W2W;

use App\Enums\Transaction\TransactionStatusEnum;
use App\Enums\Transaction\TransactionTypeEnum;
use App\Models\Account;
use App\Models\Transaction;
use Database\Seeders\CurrencySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateTransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Account
     */
    protected Account $sender;

    /**
     * @var Account
     */
    protected Account $recipient;

    public function setUp(): void
    {
        parent::setUp();

        $this->sender = Account::factory()->create();
        $this->recipient = Account::factory()->create();

        $this->seed(CurrencySeeder::class);

        $this->sender->wallets()->create([
            'currency_id' => 1,
            'external_id' => Str::uuid()
        ]);

        $this->recipient->wallets()->create([
            'currency_id' => 1,
            'external_id' => Str::uuid()
        ]);
    }

    public function test_account_can_create_w2w_transaction()
    {
        Transaction::create([
            'sender_wallet_id' => 0,
            'recipient_wallet_id' => $this->sender->id,
            'volume' => 1000,
            'status' => TransactionStatusEnum::Authorised,
            'type'=> TransactionTypeEnum::P2W
        ]);

        $response = $this->postJson(route('consumers.transactions.w2w'), [
            'recipient_id' => $this->recipient->id,
            'currency_id' => 1,
            'volume' => 100
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $senderWalletResponse = $this->getJson(route('consumers.wallets.show',1));
        $this->assertEquals(900, $senderWalletResponse->json('data.balance'));

        $recipientWalletResponse = $this->getJson(route('consumers.wallets.show',2));
        $this->assertEquals(100, $recipientWalletResponse->json('data.balance'));
    }
}
