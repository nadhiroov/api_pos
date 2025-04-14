<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    public function definition(): array
    {
        $items = [];

        // Buat antara 1–3 item per transaksi
        for ($i = 0; $i < fake()->numberBetween(1, 3); $i++) {
            $qty = fake()->numberBetween(1, 5);
            $price = fake()->numberBetween(5000, 20000);

            $items[] = [
                'product_id' => fake()->numberBetween(1, 100),
                'name' => fake()->words(2, true),
                'qty' => $qty,
                'price' => $price,
            ];
        }

        $total = collect($items)->sum(fn($item) => $item['qty'] * $item['price']);

        return [
            'branch_id' => Branch::factory(),
            'year' => now()->year,
            'transaction' => [
                [
                    'transaction_id' => 'TX-' . fake()->unique()->numerify('###'),
                    'date' => fake()->dateTimeThisYear()->format('Y-m-d H:i:s'),
                    'cashier' => fake()->userName(),
                    'items' => $items,
                    'total' => $total,
                    'payment_method' => fake()->randomElement(['cash', 'transfer', 'ewallet']),
                ]
            ],
        ];
    }
}
