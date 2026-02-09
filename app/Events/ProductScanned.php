<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductScanned implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $barcode;
    public $channelId;
    public $productData;

    public function __construct($barcode, $channelId, $productData = null)
    {
        $this->barcode = $barcode;
        $this->channelId = $channelId;
        $this->productData = $productData;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('scan.' . $this->channelId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'product.scanned';
    }
}
