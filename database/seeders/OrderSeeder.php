<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an order for user_id = 4
        $order = Order::create([
            'user_id' => 4,
            'total_price' => 0, // Will be calculated later
            'total_payment' => 60000, // Example payment
            'change' => 10000, // Example change
            'type' => 'pay_at_cashier',
            'status' => 'completed',
            'payment_status' => 'confirmed',
        ]);

        // Fetch products with IDs 1-4 from the database
        $products = Product::whereIn('id', [1, 2, 3, 4])->get();

        $totalPrice = 0;

        // Create order details for the fetched products
        foreach ($products as $product) {
            $quantity = rand(1, 5); // Random quantity for example

            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price, // Use product's price
            ]);

            // Update total price
            $totalPrice += $product->price * $quantity;
        }

        // Update the total price in the order
        $order->update([
            'total_price' => $totalPrice,
        ]);
    }
}
