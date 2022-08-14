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
        Schema::create('recipes', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->integer('remote_id')->unique();
            $table->string('type');
            $table->integer('output_item_id');
            $table->integer('output_item_count');
            $table->integer('time_to_craft_ms')->nullable();
            $table->jsonb('disciplines')->nullable();
            $table->integer('min_rating')->nullable();
            $table->jsonb('flags')->nullable();
            $table->string('chat_link')->nullable();
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
        Schema::dropIfExists('recipes');
    }
};
