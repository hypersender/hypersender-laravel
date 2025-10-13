<?php

namespace Hypersender\Hypersender\Jobs;

use Hypersender\Hypersender\Actions\CalculateWritingSecondsAction;
use Hypersender\Hypersender\Contracts\SafeSendTextMessageJobInterface;
use Hypersender\Hypersender\Exceptions\HypersenderApiErrorException;
use Hypersender\Hypersender\Facades\Hypersender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Sleep;

class SafeSendTextMessageJob implements SafeSendTextMessageJobInterface, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public array $payload)
    {
        if ($queue = config('hypersender-laravel.whatsapp_queue')) {
            $this->onQueue($queue);
        }
    }

    public function handle(): void
    {
        $response = Hypersender::whatsapp()->readChat($this->payload['chat_id']);

        $response = Hypersender::whatsapp()->startTyping($this->payload['chat_id']);

        if (! $response->successful()) {
            $body = $response->body();
            $this->fail(new HypersenderApiErrorException(responseBody: $body));

            return;
        }

        $sleepForSeconds = CalculateWritingSecondsAction::make([
            'text' => $this->payload['text'],
            'max_seconds' => 8,
        ])->run();

        Sleep::for($sleepForSeconds)->seconds();

        $sendTextResponse = Hypersender::whatsapp()->sendText(
            $this->payload['chat_id'],
            $this->payload['text'],
            $this->payload['reply_to'] ?? null,
            $this->payload['link_preview'] ?? null,
            $this->payload['link_preview_high_quality'] ?? null,
        );

        if (! $sendTextResponse->successful()) {
            $body = $sendTextResponse->body();
            $this->fail(new HypersenderApiErrorException(responseBody: $body));

            return;
        }

        $stopTypingResponse = Hypersender::whatsapp()->stopTyping($this->payload['chat_id']);

        if (! $stopTypingResponse->successful()) {
            $body = $stopTypingResponse->body();
            $this->fail(new HypersenderApiErrorException(responseBody: $body));

            return;
        }
    }
}
