<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('customer_id'); // Foreign key to customers table
            $table->unsignedBigInteger('product_id'); // Foreign key to products table
            $table->decimal('rate', 10, 2)->default(0.00)->nullable(); // Decimal field for rate
            $table->date('date')->default(now()); // Current date by default
            $table->text('details')->nullable(); // Details, optional
            $table->boolean('isActive')->default(true); // Boolean for active status, default true
            $table->timestamps(); // Created_at and updated_at

            // Foreign key constraints
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
