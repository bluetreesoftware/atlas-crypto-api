<?php

namespace Tests\Feature\Consumer\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class SignUpControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_account_with_right_data()
    {
        $response = $this->postJson(route('consumers.auth.sign-up'), [
            'email' => 'some.mail@gmail.com',
            'first_name' => 'Denis',
            'last_name' => 'Budancev',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * @dataProvider invalidData
     */
    public function test_cannot_create_account_with_invalid_data(array $data, $errors)
    {
        $response = $this->postJson(route('consumers.auth.sign-up'), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors($errors);
    }

    /**
     * @return array[]
     */
    public function invalidData(): array
    {
        return [
            [
                [
                    'email' => 'some.mail@gmail.com',
                    'first_name' => 'Denis',
                    'password' => 'password',
                    'password_confirmation' => 'password'
                ],
                ['last_name']
            ],
            [
                [
                    'email' => 'some.mail@gmail.com',
                    'last_name' => 'Budancev',
                    'password' => 'password',
                    'password_confirmation' => 'password'
                ],
                ['first_name']
            ],
            [
                [
                    'email' => 'some.mail@gmail.com',
                    'first_name' => 'Denis',
                    'last_name' => 'Budancev',
                    'password' => 'password',
                    'password_confirmation' => 'password1'
                ],
                ['password']
            ],
            [
                [
                    'first_name' => 'Denis',
                    'last_name' => 'Budancev',
                    'password' => 'password',
                    'password_confirmation' => 'password1'
                ],
                ['email']
            ]
        ];
    }
}
