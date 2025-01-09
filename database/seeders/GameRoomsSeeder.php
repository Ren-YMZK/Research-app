<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GameRoomsSeeder extends Seeder
{
    public function run()
    {
        // 外部キー制約を一時的に無効化
        Schema::disableForeignKeyConstraints();

        // テーブルをクリア
        DB::table('room_players')->truncate();
        DB::table('game_rooms')->truncate();

        // ルームデータを挿入
        DB::table('game_rooms')->insert([
            [
                'room_id' => 'A',
                'name' => 'Room A',
                'status' => 'empty',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'room_id' => 'B',
                'name' => 'Room B',
                'status' => 'empty',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'room_id' => 'C',
                'name' => 'Room C',
                'status' => 'empty',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // 外部キー制約を再度有効化
        Schema::enableForeignKeyConstraints();
    }
}
