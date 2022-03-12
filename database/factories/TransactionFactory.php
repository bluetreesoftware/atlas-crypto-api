<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * @return array
     * @throws \Exception
     */
    public function definition(): array
    {
        return [
            'sender_wallet_id' => random_int(1, 100),
            'recipient_wallet_id' => random_int(1, 100),
            'volume' => random_int(1, 100),
            'status' => random_int(1, 3)
        ];
    }
}
