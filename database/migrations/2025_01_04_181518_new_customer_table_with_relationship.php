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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company')->unique();
            $table->unsignedBigInteger('customer_type'); // Foreign key column
            $table->foreign('customer_type')->references('id')->on('customer_types')->onDelete('cascade');
            $table->date('date');
            $table->string('created_by');
            $table->string('document')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
