<?php

namespace Database\Factories;

use App\Models\BankAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BankAccount>
 */
class BankAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bank_name' => $this->faker->company . ' Bank',
            'branch_name' => $this->faker->city,
            'account_number' => $this->faker->unique()->iban(),
            'account_type' => $this->faker->randomElement(['general', 'credit_card', 'other']),
            'currency' => $this->faker->randomElement(['LYD', 'USD', 'EUR', 'AED']),
        ];
    }
}
