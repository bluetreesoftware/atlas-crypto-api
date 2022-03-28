<?php

namespace App\Services\Account;

use App\Http\Requests\Consumer\Auth\SignUpRequest;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateAccountService
{
    /**
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     * @param string $password
     * @return Account
     */
    private function createAccount(
        string $email,
        string $firstName,
        string $lastName,
        string $password
    ): Account {
        return Account::create([
            'payment_id' => Str::uuid(),
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'password' => Hash::make($password)
        ]);
    }

    /**
     * @param SignUpRequest $request
     * @return Account
     */
    public function fromRequest(SignUpRequest $request): Account
    {
        $validated = $request->validated();

        return $this->createAccount(
            $validated['email'],
            $validated['first_name'],
            $validated['last_name'],
            $validated['password']
        );
    }
}
