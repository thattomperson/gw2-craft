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
            $table->id();
            $table->integer('remoteId');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('type');
            $table->integer('level')->default(0);
            $table->string('rarity')->nullable();
            $table->integer('vendorValue')->default(0);
            $table->integer('defaultSkin')->default(0);
            $table->jsonb('gameTypes')->nullable();
            $table->jsonb('flags')->nullable();
            $table->jsonb('restrictions')->nullable();
            $table->string('chatLink')->nullable();
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
