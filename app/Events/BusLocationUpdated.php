<?php

namespace App\Events;

use App\Models\BusDetail;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BusLocationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bus;

    /**
     * Create a new event instance.
     */
    public function __construct(BusDetail $bus)
    {
        $this->bus = $bus;
    }

    /**
     * Get the channels the event should broadcast on.
     * * Logic Added: Changed to a dynamic channel name.
     * This allows the Flutter app to subscribe to 'bus-tracking.1' instead of
     * receiving updates for every bus in the system.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('bus-tracking.' . $this->bus->id),
        ];
    }

    /**
     * Logic: The name of the event as it will appear in the WebSocket.
     */
    public function broadcastAs(): string
    {
        return 'BusLocationUpdated';
    }

    /**
     * Logic Added: Explicitly define the data to be sent over the socket.
     * This ensures only the necessary coordinates and ID are broadcasted.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->bus->id,
            'latitude' => $this->bus->latitude,
            'longitude' => $this->bus->longitude,
            'updated_at' => $this->bus->updated_at->toDateTimeString(),
        ];
    }
}
