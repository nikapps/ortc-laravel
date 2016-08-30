<?php

namespace Nikapps\OrtcLaravel\Broadcasters;

use Nikapps\OrtcLaravel\OrtcLaravelFactory as Ortc;
use Illuminate\Contracts\Broadcasting\Broadcaster;

class OrtcBroadcaster implements Broadcaster
{
    /**
     * The Ortc Factory instance.
     *
     * @var Ortc
     */
    protected $ortc;

    /**
     * Create a new broadcaster instance.
     *
     * @param  Ortc $ortc
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
