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
        Schema::create('zip_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('locality')->default('');
            $table->unsignedBigInteger('federal_unit_id');
            $table->unsignedBigInteger('district_id');
            $table->foreign('federal_unit_id')->references('id')->on('federal_units');
            $table->foreign('district_id')->references('id')->on('districts');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zip_codes');
    }
};
