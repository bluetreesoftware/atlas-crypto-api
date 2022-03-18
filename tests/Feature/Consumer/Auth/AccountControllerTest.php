<?php

namespace Tests\Feature\Consumer\Auth;

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class AccountControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_account_data_after_sign_in()
    {
        $account = Account::factory()->createOne();
        $authResponse = $this->postJson(route('consumers.auth.sign-in'), [
            'email' => $account->email,
            'password' => 'password'
        ]);

        $token = $authResponse->json('data.token');

        $response = $this->getJson(route('consumers.auth.account'), [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => [
                'email' => $account->email
            ]
        ]);
    }
}
