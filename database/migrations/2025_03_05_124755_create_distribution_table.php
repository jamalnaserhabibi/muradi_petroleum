<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateDistributionTable extends Migration
{
    public function up()
    {
        Schema::create('distribution', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id');
            $table->unsignedBigInteger('distributer_id');
            $table->unsignedBigInteger('tower_id');
            $table->decimal('rate', 10, 0);
            $table->decimal('amount', 15,2);
            $table->text('details')->nullable();
            $table->timestamps();
            $table->date('date'); //set this field to default in phpmyadmin;
            // Foreign keys
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
            $table->foreign('distributer_id')->references('id')->on( 'employees')->onDelete('cascade');
            $table->foreign('tower_id')->references('id')->on('towers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('distribution');
    }
}