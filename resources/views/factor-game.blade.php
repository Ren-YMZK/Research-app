<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>因数分解ゲーム</title>
    <script src="{{ asset('js/processing.min.js') }}"></script>
    <script src="{{ asset('js/factor-game.pde') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        canvas {
            display: block;
            margin: 0 auto;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
  <a href="{{ route('home') }}" class="back-button">戻る</a>  <!-- 戻るボタンを追加 -->
  <!-- Processing.jsのキャンバス -->
  <canvas id="factorGameCanvas" width="800" height="500" data-processing-sources="{{ asset('js/factor-game.pde') }}"></canvas>

  <div class="ranking-container">
    <h2>ランキング</h2>
    <table class="ranking-table">
        <thead>
            <tr>
                <th>順位</th>
                <th>ユーザー名</th>
                <th>レベル</th>
                <th>スピード</th>
                <th>スコア</th>
            </tr>
        </thead>
        <tbody id="rankingBody">
            {{-- JavaScriptで動的に追加 --}}
        </tbody>
    </table>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    // グローバルスコープで関数を定義
    window.saveScore = function(score, level, speed) {
        console.log('スコア保存開始:', { score, level, speed }); // デバッグ用

        $.ajax({
            url: '{{ route("game.save-score") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                score: score,
                level: level,
                speed: speed
            },
            success: function(response) {
                console.log('スコア保存成功:', response);
            },
            error: function(error) {
                console.error('スコア保存エラー:', error);
            }
        });
    };

    // ProcessingのインスタンスとJavaScriptを連携
    window.onload = function() {
        let canvas = document.getElementById('factorGameCanvas');
        let processingInstance = Processing.getInstanceById('factorGameCanvas');
    };

    function updateRankings() {
        $.ajax({
            url: '{{ route("game.rankings") }}',  // ルートを追加する必要があります
            method: 'GET',
            success: function(response) {
                const rankingBody = document.getElementById('rankingBody');
                rankingBody.innerHTML = '';
                
                response.forEach((score, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${score.user.name}</td>
                        <td>${score.level}</td>
                        <td>${score.speed}</td>
                        <td>${score.score}</td>
                    `;
                    rankingBody.appendChild(row);
                });
            },
            error: function(error) {
                console.error('ランキング取得エラー:', error);
            }
        });
    }

    // ページ読み込み時とゲームオーバー時にランキングを更新
    document.addEventListener('DOMContentLoaded', updateRankings);
    window.updateRankings = updateRankings;  // Processing.jsから呼び出せるようにグローバルに設定
  </script>
</body>
</html>