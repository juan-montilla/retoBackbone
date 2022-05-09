<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zip_code', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('zip_code')->unique(); // I am well aware i could use zip code as primary key. 
            $table->string('locality');
            $table->unsignedBigInteger('federal_entity_id');
            $table->unsignedBigInteger('municipality_id');
            // $table->foreignId('federal_entity_id')->constrained('federal_entity');
            $table->foreign('federal_entity_id')->references('id')->on('federal_entity');
            $table->foreign('municipality_id')->references('id')->on('municipality');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zip_code');
    }
};
