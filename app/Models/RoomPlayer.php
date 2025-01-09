<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomPlayer extends Model
{
    protected $fillable = ['room_id', 'user_id', 'status'];

    public function room()
    {
        return $this->belongsTo(GameRoom::class, 'room_id', 'room_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
