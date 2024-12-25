<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>対戦ルーム</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="matching-container">
        <header>
            <h1>対戦ルーム</h1>
            <div id="connectionStatus">接続中...</div>
        </header>

        <div class="room-list">
            <!-- ここにルームが動的に追加されます -->
        </div>
    </div>

    <script>
        let ws;
        
        function connectWebSocket() {
            ws = new WebSocket('ws://127.0.0.1:8090');

            ws.onopen = function() {
                $('#connectionStatus').text('接続しました').addClass('connected');
            };

            ws.onclose = function() {
                $('#connectionStatus').text('接続が切れました').addClass('disconnected');
                setTimeout(connectWebSocket, 3000);
            };

            ws.onmessage = function(event) {
                const data = JSON.parse(event.data);
                handleServerMessage(data);
            };
        }

        function handleServerMessage(data) {
            switch(data.type) {
                case 'room_status':
                    updateRoomList(data.rooms);
                    break;

                case 'joined_room':
                    console.log('Joined room:', data.roomId);
                    break;

                case 'game_start':
                    console.log('Game starting, room:', data.roomId);
                    window.location.href = 'http://localhost:8080/game-multi/' + data.roomId;
                    break;

                case 'opponent_disconnected':
                    alert('対戦相手が切断しました');
                    location.reload();
                    break;

                case 'error':
                    alert(data.message);
                    break;
            }
        }

        function updateRoomList(rooms) {
            const roomList = $('.room-list');
            roomList.empty();

            rooms.forEach(room => {
                const status = getStatusText(room.status);
                const buttonDisabled = room.status === 'playing' ? 'disabled' : '';

                const roomElement = `
                    <div class="room-item ${room.status}">
                        <div class="room-info">
                            <h3>${room.name}</h3>
                            <p>状態: ${status}</p>
                            <p>プレイヤー: ${room.players}/2</p>
                        </div>
                        <button onclick="joinRoom('${room.id}')" ${buttonDisabled}>
                            ${room.status === 'empty' ? '入室' : room.status === 'waiting' ? '参加' : '対戦中'}
                        </button>
                    </div>
                `;
                roomList.append(roomElement);
            });
        }

        function getStatusText(status) {
            switch(status) {
                case 'empty': return '空き';
                case 'waiting': return '待機中';
                case 'playing': return '対戦中';
                default: return status;
            }
        }

        function joinRoom(roomId) {
            ws.send(JSON.stringify({
                type: 'join_room',
                roomId: roomId,
                userId: '{{ Auth::id() }}'
            }));
        }

        $(document).ready(connectWebSocket);
    </script>
</body>
</html> 