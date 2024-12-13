<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>因数分解ゲーム - オンライン対戦</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('js/processing.min.js') }}"></script>
    <script src="{{ asset('js/factor-game-multi.pde') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <a href="{{ route('home') }}" class="back-button">戻る</a>
    
    <div class="room-info">
        <h3>ルームID: {{ $roomId }}</h3>
        <p>このIDを対戦相手に共有してください</p>
        <input type="text" value="{{ url('/factor-game-multi/join/' . $roomId) }}" readonly>
        <button onclick="copyRoomUrl()">URLをコピー</button>
    </div>

    <div id="game-container">
        <canvas id="factorGameCanvas" width="800" height="500" 
                data-processing-sources="{{ asset('js/factor-game-multi.pde') }}"></canvas>
        <div id="opponent-info">
            <h3>対戦相手の状況</h3>
            <p>スコア: <span id="opponent-score">0</span></p>
        </div>
    </div>

    <script>
        window.Echo.channel('game.' + '{{ $roomId }}')
            .listen('GameStateUpdate', (e) => {
                document.getElementById('opponent-score').textContent = e.gameState.score;
                const pjs = Processing.getInstanceById('factorGameCanvas');
                if (pjs) {
                    pjs.updateOpponentState(e.gameState);
                }
            });

        function copyRoomUrl() {
            const input = document.querySelector('.room-info input');
            input.select();
            document.execCommand('copy');
            alert('URLをコピーしました');
        }
    </script>
</body>
</html>