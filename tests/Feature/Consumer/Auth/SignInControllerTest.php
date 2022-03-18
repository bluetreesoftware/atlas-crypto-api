<?php

namespace Tests\Feature\Consumer\Auth;

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class SignInControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_can_sign_in_with_right_data()
    {
        $account = Account::factory()->createOne();
        $response = $this->postJson(route('consumers.auth.sign-in'), [
            'email' => $account->email,
            'password' => 'password'
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'token'
            ]
        ]);
    }

    public function test_account_cannot_sign_in_with_with_wrong_data()
    {
        $account = Account::factory()->createOne();
        $response = $this->postJson(route('consumers.auth.sign-in'), [
            'email' => $account->email,
            'password' => 'password1'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
