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
</head>
<body>
  <a href="{{ route('home') }}" class="back-button">戻る</a>  <!-- 戻るボタンを追加 -->
  <!-- Processing.jsのキャンバス -->
  <canvas id="factorGameCanvas" width="800" height="500" data-processing-sources="{{ asset('js/factor-game.pde') }}"></canvas>
</body>
</html>