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
        Schema::create('mastery_levels', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->uuid('mastery_id');
            $table->string('name')->default("");
            $table->text('description')->default("");
            $table->text('instruction')->default("");
            $table->text('icon')->default("");
            $table->integer('point_cost')->default(0);
            $table->bigInteger('exp_cost')->default(0);
            $table->timestamps();

            $table->foreign('mastery_id')->references('id')->on('masteries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mastery_levels');
    }
};
