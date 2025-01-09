<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameRoom extends Model
{
    protected $fillable = ['room_id', 'name', 'status'];

    public function players()
    {
        return $this->hasMany(RoomPlayer::class, 'room_id', 'room_id');
    }
}
