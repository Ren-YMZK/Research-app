<x-app-layout>
    <div class="container">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <h2>対戦ルーム一覧</h2>
                    
                    <div class="room-list">
                        @foreach($rooms as $room)
                            <div class="room">
                                <div class="room-header">
                                    <h3>{{ $room->name }}</h3>
                                    <span>
                                        {{ $room->status === 'empty' ? '空き' : 
                                           ($room->status === 'waiting' ? '待機中' : '対戦中') }}
                                    </span>
                                </div>
                                
                                <div class="room-players">
                                    <p>参加者: {{ $room->players->count() }}/2</p>

                                    @if($room->players->count() > 0)
                                        @foreach($room->players as $player)
                                            <p class="player-name">・{{ $player->user->name }}</p>
                                        @endforeach
                                    @endif
                                </div>

                                <div class="room-actions">
                                    @if($room->status !== 'playing')
                                        @if($room->players->where('user_id', auth()->id())->count() > 0)
                                            <form action="{{ route('room.leave', $room->room_id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-leave">退室する</button>
                                            </form>
                                        @else
                                            <form action="{{ route('room.join', $room->room_id) }}" method="POST">
                                                @csrf
                                                <button type="submit" 
                                                        class="{{ $room->players->count() >= 2 ? 'btn-disabled' : 'btn-join' }}"
                                                        {{ $room->players->count() >= 2 ? 'disabled' : '' }}>
                                                    {{ $room->players->count() >= 2 ? '満員' : '入室する' }}
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <button disabled class="btn-disabled">対戦中</button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 