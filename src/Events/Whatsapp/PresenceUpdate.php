<?php

namespace Hypersender\Hypersender\Events\Whatsapp;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PresenceUpdate
{
    use Dispatchable, SerializesModels;

    public function __construct(public array $payload) {}
}
