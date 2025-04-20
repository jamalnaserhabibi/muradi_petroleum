<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE reminders MODIFY date_added TIMESTAMP NULL DEFAULT NULL");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      
    }
};
