<?php

use Illuminate\Database\Seeder;
use App\Order;
use App\Product;
use App\Item;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::truncate();
        factory(Order::class, 10)->create();

        Product::truncate();
        factory(Product::class, 10)->create();

        Item::truncate();
        factory(Item::class, 10)->create();

    }
}
