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
        Schema::create('sales', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('contract_id'); // Foreign Key to Contracts Table
            $table->unsignedBigInteger('tower_id'); // Foreign Key to Towers Table
            $table->decimal('amount', 15, 2); // Amount Field
            $table->decimal('rate', 5, 2); // Rate Fieldz
            $table->date('date');
            $table->text('description')->nullable(); // Optional Description
            $table->timestamps(); // Created_at and Updated_at

            // Foreign Key Constraints
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
            $table->foreign('tower_id')->references('id')->on('towers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
