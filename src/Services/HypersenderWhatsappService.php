<?php

namespace Hypersender\Hypersender\Services;

use Hypersender\Hypersender\Clients\Whatsapp\HypersenderWhatsappClient;
use Hypersender\Hypersender\Jobs\SafeSendTextMessageJob;
use Illuminate\Support\Traits\ForwardsCalls;

/**
 * @mixin HypersenderWhatsappClient
 */
class HypersenderWhatsappService
{
    use ForwardsCalls;

    public function __construct(
        protected HypersenderWhatsappClient $whatsappClient,
    ) {}

    /**
     * Send a text message with link preview disabled and all links removed
     *
     * @param bool|null linkPreview - Whether to show link preview (default: false)
     * @param bool|null linkPreviewHighQuality - Whether to use high quality images for link preview (default: false)
     **/
    public function safeSendTextMessage(
        string $chatId,
        string $text,
        ?string $replyTo = null,
        ?bool $linkPreview = null,
        ?bool $linkPreviewHighQuality = null,
    ): void {
        SafeSendTextMessageJob::dispatch([
            'chat_id' => $chatId,
            'text' => $text,
            'reply_to' => $replyTo,
            'link_preview' => $linkPreview,
            'link_preview_high_quality' => $linkPreviewHighQuality,
        ]);
    }

    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->whatsappClient, $method, $parameters);
    }
}
