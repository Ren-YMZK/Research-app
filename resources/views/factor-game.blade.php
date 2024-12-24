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
  </script>
</body>
</html>