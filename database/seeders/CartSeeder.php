<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cart;
use App\Models\User;
use App\Models\Product;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fetch the user with ID 4
        $user = User::find(4);
        
        // Check if the user exists
        if ($user) {
            // Fetch all products
            $products = Product::all();

            // Randomly add 2 products to the user's cart
            foreach ($products->random(2) as $product) {
                Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => rand(1, 5), // Random quantity between 1 and 5
                ]);
            }
        } else {
            // Optionally handle the case where the user does not exist
            \Log::warning('User with ID 4 does not exist. Cart entries will not be created.');
        }
    }
}
