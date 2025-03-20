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
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->text('note');
            $table->date('reminder_date')->nullable();
            $table->date('date_added');
            $table->string('created_by');
            $table->string('status')->default('pending'); // Added status field
            $table->timestamps(); // Ensure timestamps are added if needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
