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
        Schema::create('purchase', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');  
            $table->decimal('amount', 10, 2);  
            $table->decimal('heaviness', 10, 2);  
            $table->decimal('rate', 10, 2);   
            $table->date('date')->default(now()); // Current date by default 
            $table->text('details')->nullable();  
            $table->timestamps();  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase');
    }
};
