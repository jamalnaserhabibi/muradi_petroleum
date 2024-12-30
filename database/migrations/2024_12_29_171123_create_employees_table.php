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
            Schema::create('employees', function (Blueprint $table) {
                $table->id(); // Primary Key
                $table->string('fullname'); // Full name
                $table->string('photo')->nullable(); // Photo (file path)
                $table->decimal('salary', 10, 2); // Salary
                $table->date('date'); // Date
                $table->text('description')->nullable(); // Description
                $table->timestamps(); // Created_at and Updated_at
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
