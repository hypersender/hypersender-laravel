<?php

namespace Hypersender\Hypersender;

use Illuminate\Http\Client\Response;
use Illuminate\Http\UploadedFile;

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

    /**
     * Send a link custom preview by providing a URL for the image
     *
     * @param string previewImageUrl
     **/
    public function sendLinkCustomPreviewUrl(
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

    /**
     * Send a link custom preview by providing the image it self as a file upload.
     *
     * @param  UploadedFile  $previewImageFile  The image file.
     **/
    public function sendLinkCustomPreviewFile(
        string $chatId,
        string $text,
        ?string $replyTo,
        ?bool $highQuality,
        string $previewTitle,
        string $previewDescription,
        string $previewUrl,
        UploadedFile $previewImageFile,
    ): Response {
        return $this->postMultipart('/send-link-custom-preview', [
            'chatId' => $chatId,
            'text' => $text,
            'reply_to' => $replyTo,
            'high_quality' => $highQuality,
            'preview_title' => $previewTitle,
            'preview_description' => $previewDescription,
            'preview_url' => $previewUrl,
            'preview_image_file' => $previewImageFile,
        ], 'preview_image_file');
    }

    /**
     * Send a file by providing a URL for the file
     *
     * @param string url
     **/
    public function sendFileUrl(
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

    /**
     * Send a file by providing the file it self as a file upload.
     *
     * @param  UploadedFile  $file  The file to send.
     **/
    public function sendFile(
        string $chatId,
        UploadedFile $file,
        ?string $fileName = null,
        ?string $mimeType = null,
        ?string $caption = null,
        ?string $replyTo = null,
    ): Response {
        return $this->postMultipart('/send-file', [
            'chatId' => $chatId,
            'file' => $file,
            'fileName' => $fileName,
            'mimetype' => $mimeType,
            'caption' => $caption,
            'reply_to' => $replyTo,
        ], 'file');
    }

    /**
     * Send an image by providing a URL for the image
     *
     * @param string url
     **/
    public function sendImageUrl(
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

    /**
     * Send an image by providing the image it self as a file upload.
     *
     * @param  UploadedFile  $file  The image to send.
     **/
    public function sendImageFile(
        string $chatId,
        UploadedFile $file,
        ?string $fileName = null,
        ?string $mimeType = null,
        ?string $caption = null,
        ?string $replyTo = null,
    ): Response {
        return $this->postMultipart('/send-image', [
            'chatId' => $chatId,
            'file' => $file,
            'fileName' => $fileName,
            'mimetype' => $mimeType,
            'caption' => $caption,
            'reply_to' => $replyTo,
        ], 'file');
    }

    /**
     * Send a video by providing a URL for the video
     *
     * @param string url
     **/
    public function sendVideoUrl(
        string $chatId,
        string $url,
        bool $asNote,
        ?string $caption = null,
        ?string $replyTo = null,
    ): Response {
        return $this->post('/send-video', [
            'chatId' => $chatId,
            'url' => $url,
            'asNote' => $asNote,
            'caption' => $caption,
            'reply_to' => $replyTo,
        ]);
    }

    /**
     * Send a video by providing the video it self as a file upload.
     *
     * @param  UploadedFile  $file  The video to send.
     **/
    public function sendVideoFile(
        string $chatId,
        UploadedFile $file,
        bool $asNote,
        ?string $caption = null,
        ?string $replyTo = null,
    ): Response {
        return $this->postMultipart('/send-video', [
            'chatId' => $chatId,
            'file' => $file,
            'asNote' => $asNote,
            'caption' => $caption,
            'reply_to' => $replyTo,
        ], 'file');
    }

    /**
     * Send a voice by providing a URL for the voice
     *
     * @param string url
     **/
    public function sendVoiceUrl(
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

    /**
     * Send a voice by providing the voice it self as a file upload.
     *
     * @param  UploadedFile  $file  The voice to send.
     **/
    public function sendVoiceFile(
        string $chatId,
        UploadedFile $file,
        ?string $caption = null,
        ?string $replyTo = null,
    ): Response {
        return $this->postMultipart('/send-voice', [
            'chatId' => $chatId,
            'file' => $file,
            'caption' => $caption,
            'reply_to' => $replyTo,
        ], 'file');
    }

    public function forwardMessage(string $chatId, string $messageId): Response
    {
        return $this->post('/forward-message', [
            'chatId' => $chatId,
            'messageId' => $messageId,
        ]);
    }

    /**
     * Send a seen message
     *
     * @param string|null participant - Send seen for Group Message you need to provide participant field
     **/
    public function sendSeen(string $chatId, string $messageId, ?string $participant = null): Response
    {
        return $this->post('/send-seen', [
            'chatId' => $chatId,
            'messageId' => $messageId,
            'participant' => $participant,
        ]);
    }

    /**
     * Read a chat
     *
     * @param string chatId
     * @param int|null messages - How much messages to read (latest first)
     * @param int|null days - How much days to read (latest first)
     **/
    public function readChat(string $chatId, ?int $messages = null, ?int $days = null): Response
    {
        $query = [
            'messages' => $messages,
            'days' => $days,
        ];

        return $this->post('/read-chat', [
            'chatId' => $chatId,
        ], $query);
    }

    public function startTyping(string $chatId): Response
    {
        return $this->post('/start-typing', [
            'chatId' => $chatId,
        ]);
    }

    public function stopTyping(string $chatId): Response
    {
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
