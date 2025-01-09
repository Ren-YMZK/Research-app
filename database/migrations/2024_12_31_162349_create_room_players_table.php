<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('room_players', function (Blueprint $table) {
            $table->id();
            $table->string('room_id');
            $table->foreignId('user_id')->constrained();
            $table->enum('status', ['ready', 'playing']);
            $table->timestamps();

            $table->foreign('room_id')
                ->references('room_id')
                ->on('game_rooms')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('room_players');
    }
};
