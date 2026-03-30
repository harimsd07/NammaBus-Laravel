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

    public function __construct(BusDetail $bus)
    {
        $this->bus = $bus;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('bus-tracking.' . $this->bus->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'BusLocationUpdated';
    }

    // FIX: wrapped in 'bus' key — Flutter reads decodedData['bus']
    public function broadcastWith(): array
    {
        return [
            'bus' => [
                'id'         => $this->bus->id,
                'latitude'   => $this->bus->latitude,
                'longitude'  => $this->bus->longitude,
                'updated_at' => $this->bus->updated_at->toDateTimeString(),
            ],
        ];
    }
}
