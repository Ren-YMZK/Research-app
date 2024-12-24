<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameScore extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'score',
        'level',
        'speed'
    ];

    /**
     * スコアに関連するユーザーを取得
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
