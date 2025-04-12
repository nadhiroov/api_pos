<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use App\Models\Branch;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::create([
            'name'  => 'Rahat Administrator',
            'username'  => 'admin',
            'password'  => Hash::make('admin'),
            'email' => 'kentang231@gmail.com',
        ]);

        // Category::factory(20)->recycle([
        //     Shop::factory(60)->recycle([
        //         $admin,
        //         User::factory(20)->create(),
        //     ])->create()
        // ])->create();

        // Buat users
        $users = User::factory(20)->create();

        // Buat shops yang dimiliki oleh admin + user random
        $shops = Shop::factory(60)->recycle([$admin, $users])->create();

        // Buat kategori yang dimiliki oleh shop (setiap category punya shop_id)
        Category::factory(20)->recycle($shops)->create();

        // Buat branch yang terkait ke shop
        Branch::factory(100)->recycle($shops)->create();
    }
}
