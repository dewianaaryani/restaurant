<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product; // Adjust the namespace based on your Product model location
use App\Models\Stock;   // Add the Stock model namespace

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        // Seed data for the restaurant menu with English foods
        $products = [
            [
                'name' => 'Fish and Chips',
                'category' => 'main_course',
                'image' => 'img/menu-1.jpg',
                'price' => 75000,
                'stock_quantity' => 0 // Stock for this product
            ],
            [
                'name' => 'Shepherd\'s Pie',
                'category' => 'main_course',
                'image' => 'img/menu-1.jpg',
                'price' => 65000,
                'stock_quantity' => 15
            ],
            [
                'name' => 'English Breakfast Tea',
                'category' => 'drink',
                'image' => 'img/menu-1.jpg',
                'price' => 20000,
                'stock_quantity' => 30
            ],
            [
                'name' => 'Pimm\'s Cocktail',
                'category' => 'drink',
                'image' => 'img/menu-1.jpg',
                'price' => 30000,
                'stock_quantity' => 25 // Stock for this product
            ],
            [
                'name' => 'Bangers and Mash',
                'category' => 'appetizer',
                'image' => 'img/menu-1.jpg',
                'price' => 55000,
                'stock_quantity' => 20
            ],
            [
                'name' => 'Cornish Pasties',
                'category' => 'appetizer',
                'image' => 'img/menu-1.jpg',
                'price' => 40000,
                'stock_quantity' => 12
            ],
        ];

        foreach ($products as $productData) {
            // Create the product
            $product = Product::create([
                'name' => $productData['name'],
                'category' => $productData['category'],
                'image' => $productData['image'],
                'price' => $productData['price'],
            ]);

            // Create stock entry for the product
            $product->stock()->create(['quantity' => $productData['stock_quantity']]);
        }
    }
}
