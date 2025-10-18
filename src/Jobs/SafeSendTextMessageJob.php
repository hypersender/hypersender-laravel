<?php

namespace Hypersender\Jobs;

use Hypersender\Actions\CalculateWritingSecondsAction;
use Hypersender\Contracts\SafeSendTextMessageJobInterface;
use Hypersender\Exceptions\HypersenderApiErrorException;
use Hypersender\Hypersender;
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
            $this->fail(app(HypersenderApiErrorException::class, ['responseBody' => $response->body()]));

            return;
        }

        $sleepForSeconds = app(CalculateWritingSecondsAction::class, [
            'text' => $this->payload['text'],
            'maxSeconds' => 8,
        ])->execute();

        Sleep::for($sleepForSeconds)->seconds();

        $sendTextResponse = Hypersender::whatsapp()->sendText(
            $this->payload['chat_id'],
            $this->payload['text'],
            $this->payload['reply_to'] ?? null,
            $this->payload['link_preview'] ?? null,
            $this->payload['link_preview_high_quality'] ?? null,
        );

        if (! $sendTextResponse->successful()) {
            $this->fail(app(HypersenderApiErrorException::class, ['responseBody' => $sendTextResponse->body()]));

            return;
        }

        $stopTypingResponse = Hypersender::whatsapp()->stopTyping($this->payload['chat_id']);

        if (! $stopTypingResponse->successful()) {
            $this->fail(app(HypersenderApiErrorException::class, ['responseBody' => $stopTypingResponse->body()]));

            return;
        }
    }
}
