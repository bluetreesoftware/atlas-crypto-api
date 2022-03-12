<?php

namespace Tests\Feature\Consumer\Wallet;

use App\Models\Account;
use App\Models\Wallet;
use Database\Seeders\CurrencySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class WalletControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(Account::factory()->create());
        $this->seed(CurrencySeeder::class);
    }

    public function test_account_can_create_wallet()
    {
        $response = $this->postJson(route('consumers.wallets.store'), [
            'currency_id' => 2
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('wallets', [
            'account_id' => 1,
            'currency_id'=> 2
        ]);

        $this->assertDatabaseCount('wallets', 1);
    }

    public function test_account_cannot_create_wallet_with_non_existent_currency()
    {
        $response = $this->postJson(route('consumers.wallets.store'), [
            'currency_id' => 10
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
