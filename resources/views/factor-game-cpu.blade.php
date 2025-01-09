<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>因数分解ゲーム</title>
    <script src="{{ asset('js/processing.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        canvas {
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <a href="{{ route('home') }}" class="back-button">戻る</a>
    <canvas id="factorGameCanvas" data-processing-sources="{{ asset('js/factor-game-cpu.pde') }}" width="800" height="500"></canvas>

    <script>
        // スペースキーによるスクロールを防ぐ
        window.addEventListener('keydown', function(e) {
            if(e.code === 'Space') {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>