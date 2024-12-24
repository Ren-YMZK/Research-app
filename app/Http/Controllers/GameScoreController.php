<?php

namespace App\Http\Controllers;

use App\Models\GameScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GameScoreController extends Controller
{
    public function store(Request $request)
    {
        // リクエストデータをログに記録
        \Log::info('スコア保存リクエスト:', $request->all());
        \Log::info('認証ユーザー:', ['id' => auth()->id()]);

        try {
            $validated = $request->validate([
                'score' => 'required|integer|min:0',
                'level' => 'required|integer|between:1,3',
                'speed' => 'required|integer|between:1,5'
            ]);

            $score = GameScore::create([
                'user_id' => auth()->id(),
                'score' => $validated['score'],
                'level' => $validated['level'],
                'speed' => $validated['speed']
            ]);

            \Log::info('スコア保存成功:', ['score_id' => $score->id]);

            return response()->json([
                'success' => true,
                'message' => 'スコアが保存されました',
                'data' => $score
            ]);
        } catch (\Exception $e) {
            \Log::error('スコア保存エラー:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'スコアの保存に失敗しました: ' . $e->getMessage()
            ], 500);
        }
    }
}
