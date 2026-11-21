<?php

namespace Hypersender\Events\Sms;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessagePhoneUssd
{
    use Dispatchable, SerializesModels;

    public function __construct(public array $payload) {}
}
