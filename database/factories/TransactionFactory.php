<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'hours' => $this->faker->numberBetween(1, 12),
            'amount' => $this->faker->randomFloat(2, 10, 200),
            'paid' => $this->faker->boolean(),
            'date' => $this->faker->date(),
        ];
    }
}
