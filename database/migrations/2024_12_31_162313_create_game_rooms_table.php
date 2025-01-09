<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('game_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_id')->unique();
            $table->string('name');
            $table->enum('status', ['empty', 'waiting', 'playing']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('game_rooms');
    }
};
