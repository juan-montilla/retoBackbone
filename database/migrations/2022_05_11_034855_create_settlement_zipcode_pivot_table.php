<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettlementZipcodePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settlement_zipcode', function (Blueprint $table) {
            $table->unsignedBigInteger('settlement_id')->index();
            $table->foreign('settlement_id')->references('id')->on('settlements')->onDelete('cascade');
            $table->unsignedBigInteger('zipcode_id')->index();
            $table->foreign('zipcode_id')->references('id')->on('zipcodes')->onDelete('cascade');
            $table->primary(['settlement_id', 'zipcode_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settlement_zipcode');
    }
}
