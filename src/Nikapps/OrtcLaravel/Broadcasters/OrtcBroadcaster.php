<?php

namespace Illuminate\Broadcasting\Broadcasters;

use Ortc;
use Illuminate\Contracts\Broadcasting\Broadcaster;

class OrtcBroadcaster implements Broadcaster
{
    /**
     * The Pusher SDK instance.
     *
     * @var \Pusher
     */
    protected $ortc;

    /**
     * Create a new broadcaster instance.
     *
     * @param  \Pusher  $pusher
     * @return void
     */
    public function __construct(Ortc $ortc)
    {
        $this->ortc = $ortc;
    }

    /**
     * {@inheritdoc}
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {
        $this->ortc->trigger($channels, $event, $payload);
    }
}
