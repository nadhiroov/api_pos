<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Category;
use App\Models\Role;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $year = now()->year;

        // 👤 Buat 1 admin
        $admin = User::create([
            'name'     => 'Rahat Administrator',
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'email'    => 'kentang231@gmail.com',
        ]);

        Role::create([
            'role_name' => 'admin'
        ]);

        Role::create([
            'role_name' => 'cashier'
        ]);

        // 👥 Buat users tambahan
        $users = User::factory(20)->create();

        // 🏬 Buat shops milik admin dan user
        $shops = Shop::factory(60)->recycle([$admin, $users])->create();

        // 🏷️ Buat kategori milik shop
        $categories = Category::factory(20)->recycle($shops)->create();

        // 🏢 Buat branch milik shop
        $branches = Branch::factory(100)->recycle($shops)->create();

        // 📦 Buat produk milik category & branch
        Product::factory(300)->recycle([$categories, $branches])->create();

        // 💵 Buat transaksi: hanya 1 record per branch per tahun
        foreach ($branches as $branch) {
            $transactions = [];

            for ($i = 0; $i < 20; $i++) {
                $items = [];

                for ($j = 0; $j < rand(1, 3); $j++) {
                    $qty = rand(1, 5);
                    $price = rand(5000, 20000);
                    $items[] = [
                        'product_id' => rand(1, 300),
                        'name'       => $faker->words(2, true),
                        'qty'        => $qty,
                        'price'      => $price,
                    ];
                }

                $total = collect($items)->sum(fn($item) => $item['qty'] * $item['price']);

                $transactions[] = [
                    'transaction_id'   => 'TX-' . strtoupper(Str::random(6)),
                    'date'             => $faker->dateTimeBetween("$year-01-01", "$year-12-31")->format('Y-m-d H:i:s'),
                    'cashier'          => $faker->userName(),
                    'items'            => $items,
                    'total'            => $total,
                    'payment_method'   => $faker->randomElement(['cash', 'transfer', 'ewallet']),
                ];
            }

            Transaction::updateOrCreate(
                ['branch_id' => $branch->id, 'year' => $year],
                ['transaction' => $transactions]
            );
        }


    }
}
