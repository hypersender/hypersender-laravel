<?php

namespace Hypersender\Events\Whatsapp;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PollVoteFailed
{
    use Dispatchable, SerializesModels;

    public function __construct(public array $payload) {}
}
