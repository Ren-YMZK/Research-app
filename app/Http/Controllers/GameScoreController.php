<?php

namespace App\Http\Controllers;

use App\Models\GameScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameScoreController extends Controller
{
    public function store(Request $request)
    {
        // リクエストデータのバリデーション
        $validated = $request->validate([
            'score' => 'required|integer|min:0',
            'level' => 'required|integer|between:1,3',
            'speed' => 'required|integer|between:1,5'
        ]);

        try {
            // スコアの保存
            $score = GameScore::create([
                'user_id' => Auth::id(),
                'score' => $validated['score'],
                'level' => $validated['level'],
                'speed' => $validated['speed']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'スコアが保存されました'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'スコアの保存に失敗しました'
            ], 500);
        }
    }
}
