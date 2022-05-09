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
        Schema::create('rel_zipcode_settlement', function (Blueprint $table) {
            $table->id(); // alias bigIncrement()
            $table->string('zip_code');
            $table->unsignedInteger('settlement_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rel_zipcode_settlement');
    }
};
