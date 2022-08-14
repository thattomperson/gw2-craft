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
        Schema::create('items', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->integer('remote_id')->unique();
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->string('type')->nullable();
            $table->integer('level')->default(0);
            $table->string('rarity')->nullable();
            $table->integer('vendor_value')->default(0);
            $table->integer('default_skin')->default(0);
            $table->jsonb('game_types')->nullable();
            $table->jsonb('flags')->nullable();
            $table->jsonb('restrictions')->nullable();
            $table->string('chat_link')->nullable();
            $table->string('icon')->nullable();
            $table->jsonb('details')->nullable();
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
        Schema::dropIfExists('items');
    }
};
