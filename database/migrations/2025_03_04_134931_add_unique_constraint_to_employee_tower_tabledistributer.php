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
        Schema::table('distributer', function (Blueprint $table) {
            // Add unique constraint on tower_id
            $table->unique('tower_id');
        });
    }
    
    public function down()
    {
        Schema::table('distributer', function (Blueprint $table) {
            // Drop the unique constraint
            $table->dropUnique('employee_tower_tower_id_unique');
        });
    }
};
