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
        Schema::create('sarafi_pickup', function (Blueprint $table) {
            $table->id();
            $table->date('date'); // تاریخ
            $table->string('az_darak'); // از درک
            $table->string('toAccount'); 
            $table->decimal('amount', 15, 2); // مقدار به افغانی
            $table->text('details')->nullable(); // جزئیات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sarafi_pickups');
    }
};
