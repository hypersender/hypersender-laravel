<?php

namespace Hypersender\Hypersender\Enums;

use Hypersender\Hypersender\Events\PresenceUpdate;

enum WhatsappWebhookEventEnum: string
{
    case PresenceUpdate = 'presence.update';

    public function eventClass(): string
    {
        return match ($this) {
            self::PresenceUpdate => PresenceUpdate::class,
        };
    }
}
