<?php

namespace Hypersender\Hypersender\Jobs;

use Hypersender\Hypersender\Contracts\WhatsappWebhookJobInterface;
use Hypersender\Hypersender\Enums\WhatsappWebhookEventEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;
use InvalidArgumentException;

class ProcessWhatsappWebhookJob implements WhatsappWebhookJobInterface
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public array $payload,
        public ?string $signature = null,
    ) {}

    public function handle(): void
    {
        $eventName = $this->payload['event'] ?? null;
        if (! is_string($eventName)) {
            throw new InvalidArgumentException('Invalid payload: missing event.');
        }

        $eventEnum = WhatsappWebhookEventEnum::tryFrom($eventName);
        if ($eventEnum === null) {
            throw new InvalidArgumentException("Unsupported webhook event '{$eventName}'.");
        }

        $eventClass = $eventEnum->eventClass();

        Event::dispatch(new $eventClass($this->payload));
    }
}
