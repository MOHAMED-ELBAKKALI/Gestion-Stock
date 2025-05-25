<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Stock;
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
        $transactionableTypes = [
            Order::class,
            Stock::class,
        ];

        $type = fake()->randomElement($transactionableTypes);
        $model = $type::factory()->create();

        return [
            'transactionel_type' => $type,
            'transactionel_id' => $model->id,
            'operation' => fake()->randomElement(['created', 'updated', 'deleted']),
        ];
    }
}
