<?php

namespace Hypersender\Events\Whatsapp;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageAny
{
    use Dispatchable, SerializesModels;

    public function __construct(public array $payload) {}
}
