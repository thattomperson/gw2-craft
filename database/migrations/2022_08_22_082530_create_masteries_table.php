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
        Schema::create('masteries', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->integer('remote_id')->unique();
            $table->string('name')->default("");
            $table->text('requirement')->default("");
            $table->integer('order')->default(0);
            $table->string('background')->default("");
            $table->string('region')->default("");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('masteries');
    }
};
