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
        Schema::create('hesabSherkat_payment', function (Blueprint $table) {
            $table->id(); 
            $table->string('fromPerson');
            $table->string('fromChannel');
            $table->string('supplier');
            $table->decimal('amount', 10, 2);  
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
        Schema::dropIfExists('hesabSherkat_payment');
    }
};
