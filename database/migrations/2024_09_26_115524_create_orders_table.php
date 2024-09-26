<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key to users table
            $table->decimal('total_price', 10, 2); // Total price of the order
            $table->string('status')->default('pending'); // Status of the order (e.g., pending, completed, etc.)
            $table->string('payment_status')->default('unpaid'); // Payment status of the order (e.g., unpaid, paid, etc.)
            $table->string('image')->nullable(); // Payment status of the order (e.g., unpaid, paid, etc.)
            $table->timestamps(); // Created at and updated at
        });
        Schema::create('order_details', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // Foreign key to orders table
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Foreign key to products table
            $table->integer('quantity'); // Quantity of the product in the order
            $table->decimal('price', 10, 2); // Price of the product at the time of the order
            $table->timestamps(); // Created at and updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('orders');
    }
};
