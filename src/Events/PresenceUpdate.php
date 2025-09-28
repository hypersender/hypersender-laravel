<?php

namespace Hypersender\Hypersender\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PresenceUpdate
{
    use Dispatchable, SerializesModels;

    public function __construct(public array $payload) {}
}
