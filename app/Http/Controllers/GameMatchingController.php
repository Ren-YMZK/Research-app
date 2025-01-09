<?php

namespace App\Http\Controllers;

use App\Models\GameRoom;
use App\Models\RoomPlayer;
use Illuminate\Http\Request;

class GameMatchingController extends Controller
{
  public function index()
  {
    // ルーム一覧を取得（プレイヤー情報も含める）
    $rooms = GameRoom::with(['players.user'])->get();
    return view('game-matching', ['rooms' => $rooms]);
  }

  public function join($roomId)
  {
    $room = GameRoom::where('room_id', $roomId)->firstOrFail();

    // 既に参加しているか確認
    if ($room->players->where('user_id', auth()->id())->count() > 0) {
      return back()->with('error', '既に参加しています');
    }

    // 部屋が満員でないか確認
    if ($room->players->count() >= 2) {
      return back()->with('error', '部屋が満員です');
    }

    // プレイヤーを追加
    RoomPlayer::create([
      'room_id' => $roomId,
      'user_id' => auth()->id(),
      'status' => 'ready'
    ]);

    // 部屋の状態を更新
    $room->status = $room->players->count() + 1 >= 2 ? 'playing' : 'waiting';
    $room->save();

    // 2人揃った場合はゲーム画面へリダイレクト
    if ($room->status === 'playing') {
      return redirect()->route('game.multi', ['roomId' => $roomId]);
    }

    return back()->with('success', '入室しました');
  }

  public function leave($roomId)
  {
    $room = GameRoom::where('room_id', $roomId)->firstOrFail();

    // プレイヤーを削除
    RoomPlayer::where('room_id', $roomId)
      ->where('user_id', auth()->id())
      ->delete();

    // 部屋の状態を更新
    $remainingPlayers = RoomPlayer::where('room_id', $roomId)->count();
    $room->status = $remainingPlayers === 0 ? 'empty' : 'waiting';
    $room->save();

    return back()->with('success', '退室しました');
  }
}
