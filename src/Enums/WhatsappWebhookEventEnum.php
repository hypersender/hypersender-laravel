<?php

namespace Hypersender\Enums;

use Hypersender\Events\Whatsapp\MessageAck;
use Hypersender\Events\Whatsapp\MessageAny;
use Hypersender\Events\Whatsapp\MessageReaction;
use Hypersender\Events\Whatsapp\MessageRevoked;
use Hypersender\Events\Whatsapp\MessageWaiting;
use Hypersender\Events\Whatsapp\PollVote;
use Hypersender\Events\Whatsapp\PollVoteFailed;
use Hypersender\Events\Whatsapp\PresenceUpdate;

enum WhatsappWebhookEventEnum: string
{
    case PresenceUpdate = 'presence.update';
    case MessageAny = 'message.any';
    case MessageReaction = 'message.reaction';
    case MessageAck = 'message.ack';
    case MessageWaiting = 'message.waiting';
    case MessageRevoked = 'message.revoked';
    case PollVote = 'poll.vote';
    case PollVoteFailed = 'poll.vote.failed';

    public function eventClass(): string
    {
        return match ($this) {
            self::PresenceUpdate => PresenceUpdate::class,
            self::MessageAny => MessageAny::class,
            self::MessageReaction => MessageReaction::class,
            self::MessageAck => MessageAck::class,
            self::MessageWaiting => MessageWaiting::class,
            self::MessageRevoked => MessageRevoked::class,
            self::PollVote => PollVote::class,
            self::PollVoteFailed => PollVoteFailed::class,
        };
    }
}
