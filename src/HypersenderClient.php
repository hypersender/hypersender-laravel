<?php

namespace Hypersender\Hypersender;

use Illuminate\Http\Client\Response;

class HypersenderClient extends AbstractClient
{
    public function sendTextSafe(
        string $chatId,
        string $text,
        ?string $replyTo = null,
        ?bool $linkPreview = null,
        ?bool $linkPreviewHighQuality = null,
    ): Response {
        return $this->post('/send-text-safe', [
            'chatId' => $chatId,
            'text' => $text,
            'reply_to' => $replyTo,
            'link_preview' => $linkPreview,
            'link_preview_high_quality' => $linkPreviewHighQuality,
        ]);
    }

    public function sendText(
        string $chatId,
        string $text,
        ?string $replyTo = null,
        ?bool $linkPreview = null,
        ?bool $linkPreviewHighQuality = null,
    ): Response {
        return $this->post('/send-text', [
            'chatId' => $chatId,
            'text' => $text,
            'reply_to' => $replyTo,
            'link_preview' => $linkPreview,
            'link_preview_high_quality' => $linkPreviewHighQuality,
        ]);
    }

    public function sendLinkCustomPreview(
        string $chatId,
        string $text,
        ?string $replyTo,
        ?bool $highQuality,
        string $previewTitle,
        string $previewDescription,
        string $previewUrl,
        string $previewImageUrl,
    ): Response {
        return $this->post('/send-link-custom-preview', [
            'chatId' => $chatId,
            'text' => $text,
            'reply_to' => $replyTo,
            'high_quality' => $highQuality,
            'preview_title' => $previewTitle,
            'preview_description' => $previewDescription,
            'preview_url' => $previewUrl,
            'preview_image_url' => $previewImageUrl,
        ]);
    }

    public function sendFile(
        string $chatId,
        string $url,
        ?string $fileName = null,
        ?string $mimeType = null,
        ?string $caption = null,
        ?string $replyTo = null,
    ): Response {
        return $this->post('/send-file', [
            'chatId' => $chatId,
            'url' => $url,
            'fileName' => $fileName,
            'mimetype' => $mimeType,
            'caption' => $caption,
            'reply_to' => $replyTo,
        ]);
    }

    public function sendImage(
        string $chatId,
        string $url,
        ?string $fileName = null,
        ?string $mimeType = null,
        ?string $caption = null,
        ?string $replyTo = null,
    ): Response {
        return $this->post('/send-image', [
            'chatId' => $chatId,
            'url' => $url,
            'fileName' => $fileName,
            'mimetype' => $mimeType,
            'caption' => $caption,
            'reply_to' => $replyTo,
        ]);
    }

    public function sendVideo(
        string $chatId,
        string $url,
        bool $asNote,
        ?string $caption = null,
        ?string $replyTo = null,
    ): Response {
        return $this->post('/send-video', [
            'chatId' => $chatId,
            'url' => $url,
            'as_note' => $asNote,
            'caption' => $caption,
            'reply_to' => $replyTo,
        ]);
    }

    public function sendVoice(
        string $chatId,
        string $url,
        ?string $caption = null,
        ?string $replyTo = null,
    ): Response {
        return $this->post('/send-voice', [
            'chatId' => $chatId,
            'url' => $url,
            'caption' => $caption,
            'reply_to' => $replyTo,
        ]);
    }

    public function forwardMessage(
        string $chatId,
        string $messageId,
    ): Response {
        return $this->post('/forward-message', [
            'chatId' => $chatId,
            'messageId' => $messageId,
        ]);
    }

    public function sendSeen(
        string $chatId,
        string $messageId,
        ?string $participant = null,
    ): Response {
        return $this->post('/send-seen', [
            'chatId' => $chatId,
            'messageId' => $messageId,
            'participant' => $participant,
        ]);
    }

    // TODO: Add the read chat method

    public function startTyping(
        string $chatId,
    ): Response {
        return $this->post('/start-typing', [
            'chatId' => $chatId,
        ]);
    }

    public function stopTyping(
        string $chatId,
    ): Response {
        return $this->post('/stop-typing', [
            'chatId' => $chatId,
        ]);
    }

    // TODO: Add the send poll method

    // TODO: Add the send contact card method

    // TODO: Add the send location method

    // TODO: Add the react to message method

    // TODO: Add the star message method

    // TODO: Add the delete message method

}
