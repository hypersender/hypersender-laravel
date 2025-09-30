<?php

namespace Hypersender\Hypersender\Clients\Sms;

use Hypersender\Hypersender\AbstractClient;
use Hypersender\Hypersender\Http\Requests\Sms\HeartbeatsIndexRequest;
use Hypersender\Hypersender\Http\Requests\Sms\MessagesIndexRequest;
use Hypersender\Hypersender\Http\Requests\Sms\MessageThreadsIndexRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Config;

class HypersenderSmsClient extends AbstractClient
{
    public function __construct()
    {
        $baseUrl = Config::get('hypersender-laravel.sms_base_url', env('HYPERSENDER_SMS_BASE_URL'));
        $apiKey = Config::get('hypersender-laravel.sms_api_key', env('HYPERSENDER_SMS_API_KEY'));
        $instanceId = Config::get('hypersender-laravel.sms_instance_id', env('HYPERSENDER_SMS_INSTANCE_ID'));

        parent::__construct($baseUrl, $apiKey, $instanceId);
    }

    public function messages(?MessagesIndexRequest $request = null): Response
    {
        $query = $request?->toQuery();

        return $this->get('/messages', $query ?? []);
    }

    /**
     * @param string content: The content of your SMS message
     * @param string requestId: any string preferably uuid that you can use later to find this message.
     * @param string to: The phone number of the recipient. e.g. +201234567890
     * @param string|null scheduleSendAt: optional date and time to schedule the message for sending. e.g. 2025-01-01 12:00:00
     * @param int|null maxSendAttempts: how many times to retry sending the message if it fails. Max: 15 times.
     * @param int|null messageExpirationSeconds: How many seconds this message is valid for, this is usefull when you are sending OTPs with expiration of 2 minutes.
     */
    public function sendMessage(
        string $content,
        string $requestId,
        string $to,
        ?string $scheduleSendAt = null,
        ?int $maxSendAttempts = null,
        ?int $messageExpirationSeconds = null,
    ): Response {
        return $this->post('/send-message', [
            'content' => $content,
            'request_id' => $requestId,
            'to' => $to,
            'schedule_send_at' => $scheduleSendAt,
            'max_send_attempts' => $maxSendAttempts,
            'message_expiration_seconds' => $messageExpirationSeconds,
        ]);
    }

    /**
     * @param string messageId: The ID of the message to delete.
     */
    public function deleteMessage(string $messageId): Response
    {
        return $this->delete('/delete-message', [], ['message_id' => $messageId]);
    }

    public function messageThreads(?MessageThreadsIndexRequest $request = null): Response
    {
        $query = $request?->toQuery();

        return $this->get('/message-threads', $query ?? []);
    }

    /**
     * @param  bool  $isArchived:  Specifies whether the message thread should be marked as archived.
     * @param  string  $messageThreadId:  The ID of the message thread to edit.
     */
    public function editMessageThread(bool $isArchived, string $messageThreadId): Response
    {
        return $this->put('/edit-message-thread', [
            'is_archived' => $isArchived,
            'message_thread_id' => $messageThreadId,
        ]);
    }

    public function deleteMessageThread(string $messageThreadId): Response
    {
        return $this->delete('/delete-message-thread', [], ['message_thread_id' => $messageThreadId]);
    }

    public function getHeartbeats(?HeartbeatsIndexRequest $request = null): Response
    {
        $query = $request?->toQuery();

        return $this->get('/heartbeats', $query ?? []);
    }
}
