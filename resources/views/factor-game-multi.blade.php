<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>因数分解対戦</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="{{ asset('js/processing.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="game-container">
        <div class="game-header">
            <div class="player-info">
                <span>あなた: <span id="myScore">0</span>点</span>
                <span class="vs">VS</span>
                <span>対戦相手: <span id="opponentScore">0</span>点</span>
            </div>
            <div id="connectionStatus">接続中...</div>
        </div>

        <div id="gameCanvas" class="canvas-container">
            <canvas id="factorGameCanvas" data-processing-sources="{{ asset('js/factor-game-multi.pde') }}"></canvas>
        </div>
    </div>

    <script>
        let ws;
        const roomId = '{{ $roomId }}';
        
        function connectWebSocket() {
            ws = new WebSocket('ws://127.0.0.1:8090');

            ws.onopen = function() {
                $('#connectionStatus').text('接続しました').addClass('connected');
                ws.send(JSON.stringify({
                    type: 'join_room',
                    roomId: roomId,
                    userId: '{{ Auth::id() }}'
                }));
            };

            ws.onmessage = function(event) {
                const data = JSON.parse(event.data);
                
                if (data.type === 'game_start' && window.pjs) {
                    window.pjs.startGame(data.problem);
                }
            };

            ws.onclose = function() {
                $('#connectionStatus').text('接続が切れました').addClass('disconnected');
                alert('接続が切れました。ページを更新してください。');
            };
        }

        $(document).ready(connectWebSocket);
    </script>
</body>
</html>