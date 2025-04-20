<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // For MySQL
        DB::statement("ALTER TABLE customers MODIFY date TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // For MySQL
        DB::statement("ALTER TABLE customers MODIFY date TIMESTAMP NULL DEFAULT NULL");
        
        // Or for cross-database compatibility:
        // Schema::table('customers', function (Blueprint $table) {
        //     $table->timestamp('date')->nullable()->change();
        // });
    }
};
