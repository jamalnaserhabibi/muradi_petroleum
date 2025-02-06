<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('payment', function (Blueprint $table) {
        $table->id(); 
        $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade');
        $table->decimal('amount', 10, 1);  
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
        //
    }
};
