<?php

namespace Hypersender\Hypersender\Events\Sms;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageNotificationScheduled
{
    use Dispatchable, SerializesModels;

    public function __construct(public array $payload) {}
}
