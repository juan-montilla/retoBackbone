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
        Schema::create('federal_units', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique();
            $table->string('name')->unique();
            $table->string('code')->nullable();
            $table->primary('id');
            $table->index(['id','name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('federal_units');
    }
};
