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
        Schema::create('sarafi_payments', function (Blueprint $table) {
            $table->id();
            $table->date('date'); // تاریخ
            $table->string('az_darak'); // از درک
            $table->decimal('amount_afghani', 15, 2); // مقدار به افغانی
            $table->decimal('equivalent_dollar', 15, 2); // معادل به دالر
            $table->decimal('amount_dollar', 15, 2); // مقدار دالر
            $table->decimal('moaadil_afghani', 15, 2); // معادل به افغانی
            $table->text('details')->nullable(); // جزئیات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sarafi_payments');
    }
};
