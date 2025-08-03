<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{

    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name' => $this->faker->name(),
            'phone' => $this->faker->unique()->numerify('091#######'),
            'email' => $this->faker->unique()->safeEmail(),
            'license' => $this->faker->optional()->numerify('L#######'),
            'address' => $this->faker->optional()->address(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'date_of_birth' => $this->faker->optional()->date(),
            'marital_status' => $this->faker->randomElement(['married', 'single']),
            'role' => $this->faker->randomElement(['employee', 'accountant', 'driver', 'manager', 'sales', 'hr', 'supervisor']),
            'password' => static::$password ??= Hash::make('091091'),
        ];
    }
}
