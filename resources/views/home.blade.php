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
        <div class="auth-links">
            @guest
                {{-- 未ログインの場合 --}}
                <a href="{{ route('login') }}" class="auth-link">ログイン</a>
                <a href="{{ route('register') }}" class="auth-link">新規登録</a>
            @else
                {{-- ログイン済みの場合 --}}
                <span class="user-name">{{ Auth::user()->name }}さん</span>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-button">ログアウト</button>
                </form>
            @endguest
        </div>
    </div>
</body>
</html>