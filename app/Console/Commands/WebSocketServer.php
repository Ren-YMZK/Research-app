<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Workerman\Worker;

class WebSocketServer extends Command
{
  protected $signature = 'websocket:serve';
  protected $description = 'Start WebSocket server';

  private $connections = [];
  private $rooms = [
    'A' => ['id' => 'A', 'name' => 'Room A', 'players' => [], 'status' => 'empty'],
    'B' => ['id' => 'B', 'name' => 'Room B', 'players' => [], 'status' => 'empty'],
    'C' => ['id' => 'C', 'name' => 'Room C', 'players' => [], 'status' => 'empty']
  ];

  public function handle()
  {
    $ws_worker = new Worker('websocket://0.0.0.0:8090');
    $ws_worker->count = 1;

    $ws_worker->onWorkerStart = function ($worker) {
      $this->info('WebSocket Server started on port 8090');
    };

    $ws_worker->onConnect = function ($connection) {
      $connection->id = uniqid();
      $this->connections[$connection->id] = $connection;
      $this->info("New connection: {$connection->id}");

      // 接続時に現在の部屋の状態を送信
      $this->sendRoomStatus($connection);
    };

    $ws_worker->onMessage = function ($connection, $data) {
      $this->info("Received message: " . $data);

      try {
        $message = json_decode($data, true);
        if (!$message || !isset($message['type'])) {
          throw new \Exception('Invalid message format');
        }

        switch ($message['type']) {
          case 'join_room':
            $this->handleJoinRoom($connection, $message);
            break;

          default:
            $this->error("Unknown message type: {$message['type']}");
            break;
        }
      } catch (\Exception $e) {
        $this->error($e->getMessage());
        $connection->send(json_encode([
          'type' => 'error',
          'message' => $e->getMessage()
        ]));
      }
    };

    $ws_worker->onClose = function ($connection) {
      $this->info("Connection closed: {$connection->id}");
      $this->handleDisconnect($connection);
      unset($this->connections[$connection->id]);
    };

    Worker::runAll();
  }

  private function handleJoinRoom($connection, $message)
  {
    if (!isset($message['roomId']) || !isset($message['userId'])) {
      throw new \Exception('Room ID and user ID are required');
    }

    $roomId = $message['roomId'];
    if (!isset($this->rooms[$roomId])) {
      throw new \Exception('Invalid room ID');
    }

    $room = &$this->rooms[$roomId];

    // 部屋が満員の場合
    if (count($room['players']) >= 2) {
      throw new \Exception('Room is full');
    }

    // プレイヤーを追加
    $room['players'][] = [
      'connection' => $connection,
      'userId' => $message['userId']
    ];

    // 部屋の状態を更新
    $room['status'] = count($room['players']) === 1 ? 'waiting' : 'playing';

    // 参加者に通知
    $connection->send(json_encode([
      'type' => 'joined_room',
      'roomId' => $roomId
    ]));

    // 2人揃った場合、ゲーム開始
    if (count($room['players']) === 2) {
      $problem = $this->generateProblem();
      foreach ($room['players'] as $player) {
        $player['connection']->send(json_encode([
          'type' => 'game_start',
          'problem' => $problem
        ]));
      }
    }

    // 全員に部屋の状態を更新
    $this->broadcastRoomStatus();
  }

  private function handleDisconnect($connection)
  {
    foreach ($this->rooms as $roomId => &$room) {
      foreach ($room['players'] as $key => $player) {
        if ($player['connection'] === $connection) {
          // プレイヤーを削除
          unset($room['players'][$key]);
          $room['players'] = array_values($room['players']);

          // 部屋の状態を更新
          $room['status'] = count($room['players']) === 0 ? 'empty' : 'waiting';

          // 残りのプレイヤーに通知
          if (!empty($room['players'])) {
            $room['players'][0]['connection']->send(json_encode([
              'type' => 'opponent_disconnected'
            ]));
          }

          // 全員に部屋の状態を更新
          $this->broadcastRoomStatus();
          break 2;
        }
      }
    }
  }

  private function sendRoomStatus($connection)
  {
    $roomStatus = [];
    foreach ($this->rooms as $room) {
      $roomStatus[] = [
        'id' => $room['id'],
        'name' => $room['name'],
        'players' => count($room['players']),
        'status' => $room['status']
      ];
    }

    $connection->send(json_encode([
      'type' => 'room_status',
      'rooms' => $roomStatus
    ]));
  }

  private function broadcastRoomStatus()
  {
    foreach ($this->connections as $connection) {
      $this->sendRoomStatus($connection);
    }
  }

  private function generateProblem()
  {
    $problems = [
      'x^2 + 5x + 6',
      'x^2 - 7x + 12',
      'x^2 + 2x - 15',
      'x^2 - 4x - 12',
      'x^2 + 6x + 9'
    ];
    return $problems[array_rand($problems)];
  }
}
