<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research App</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <div class="container">
        <h1>Welcome to Research App</h1>
        <p>This is the app of my research.</p>
        <div class="game-links">
            <a href="{{ route('factor-game') }}" class="game-button">因数分解ゲームを始める</a>
            <a href="#" class="game-button">他のゲームを追加</a>
        </div>
    </div>
</body>
</html>