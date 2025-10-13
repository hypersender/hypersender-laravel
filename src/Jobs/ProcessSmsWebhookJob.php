<?php

namespace Hypersender\Hypersender\Jobs;

use Hypersender\Hypersender\Contracts\SmsWebhookJobInterface;
use Hypersender\Hypersender\Enums\SmsWebhookEventEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use InvalidArgumentException;

class ProcessSmsWebhookJob implements ShouldQueue, SmsWebhookJobInterface
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public array $payload, public ?string $secret = null)
    {
        if ($queue = config('hypersender-laravel.sms_queue')) {
            $this->onQueue($queue);
        }
    }

    public function handle(): void
    {
        try {
            $eventName = $this->payload['event'] ?? null;
            if (! is_string($eventName)) {
                throw new InvalidArgumentException('Invalid payload: missing event.');
            }

            event(
                app(SmsWebhookEventEnum::from($eventName)->eventClass(), [
                    'payload' => $this->payload,
                ])
            );
        } catch (\Throwable $e) {
            report($e);

            $this->fail($e);

            return;
        }
    }
}
