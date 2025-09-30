<?php

use Hypersender\Hypersender\Facades\Hypersender;
use Hypersender\Hypersender\Http\Requests\Sms\MessagesIndexRequest;
use Hypersender\Hypersender\Http\Requests\Sms\MessageThreadsIndexRequest;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->endpoint = config('hypersender-laravel.sms_base_url');
    $this->instanceId = config('hypersender-laravel.sms_instance_id');
});

it('can index messages', function () {
    $payload = indexMessagesEndpointPayload();

    Http::fake([
        '*' => Http::response($payload, 200),
    ]);

    $messagesRequest = new MessagesIndexRequest(
        perPage: '50',
        status: 'delivered',
        page: '2',
    );

    $expectedQuery = $messagesRequest->toQuery();

    $response = Hypersender::sms()->messages($messagesRequest);

    Http::assertSent(function (Request $request) use ($expectedQuery) {
        return str_starts_with($request->url(), "{$this->endpoint}/{$this->instanceId}/messages")
            && $request->data() === $expectedQuery;
    });

    expect($response->json())->toBe($payload);
});

it('can send sms message normally with required parameters only', function () {
    $payload = sendSmsMessageEndpointPayload();

    Http::fake([
        '*' => Http::response($payload, 200),
    ]);

    $content = 'Hello, world!';
    $requestId = 'req_abcdef1234567890';
    $to = '+201234567890';

    $response = Hypersender::sms()->sendMessage($content, $requestId, $to);

    Http::assertSent(function (Request $request) use ($content, $requestId, $to) {
        return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-message"
            && $request->data() === [
                'content' => $content,
                'request_id' => $requestId,
                'to' => $to,
                'schedule_send_at' => null,
                'max_send_attempts' => null,
                'message_expiration_seconds' => null,
            ];
    });

    expect($response->json())->toBe($payload);
});

it('can send scheduled sms message', function () {
    $payload = sendSmsMessageEndpointPayload();

    Http::fake([
        '*' => Http::response($payload, 200),
    ]);

    $content = 'Hello, scheduled!';
    $requestId = 'req_scheduled_123';
    $to = '+201234567891';
    $scheduleSendAt = '2025-01-01 12:00:00';

    $response = Hypersender::sms()->sendMessage(
        $content,
        $requestId,
        $to,
        $scheduleSendAt,
    );

    Http::assertSent(function (Request $request) use ($content, $requestId, $to, $scheduleSendAt) {
        return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-message"
            && $request->data() === [
                'content' => $content,
                'request_id' => $requestId,
                'to' => $to,
                'schedule_send_at' => $scheduleSendAt,
                'max_send_attempts' => null,
                'message_expiration_seconds' => null,
            ];
    });

    expect($response->json())->toBe($payload);
});

it('can send sms with max attempts and expiration seconds', function () {
    $payload = sendSmsMessageEndpointPayload();

    Http::fake([
        '*' => Http::response($payload, 200),
    ]);

    $content = 'Hello with options!';
    $requestId = 'req_with_options_123';
    $to = '+201234567892';
    $maxSendAttempts = 5;
    $messageExpirationSeconds = 180;

    $response = Hypersender::sms()->sendMessage(
        $content,
        $requestId,
        $to,
        null,
        $maxSendAttempts,
        $messageExpirationSeconds,
    );

    Http::assertSent(function (Request $request) use ($content, $requestId, $to, $maxSendAttempts, $messageExpirationSeconds) {
        return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-message"
            && $request->data() === [
                'content' => $content,
                'request_id' => $requestId,
                'to' => $to,
                'schedule_send_at' => null,
                'max_send_attempts' => $maxSendAttempts,
                'message_expiration_seconds' => $messageExpirationSeconds,
            ];
    });

    expect($response->json())->toBe($payload);
});

it('can delete sms message', function () {
    Http::fake([
        '*' => Http::response([], 200),
    ]);

    $response = Hypersender::sms()->deleteMessage('msg_1234567890abcdef');

    Http::assertSent(function (Request $request) {
        return $request->url() === "{$this->endpoint}/{$this->instanceId}/delete-message?message_id=msg_1234567890abcdef"
            && $request->data() === [];
    });

    expect($response->json())->toBe([]);
});

it('can index message threads', function () {
    $payload = indexMessageThreadsEndpointPayload();

    Http::fake([
        '*' => Http::response($payload, 200),
    ]);

    $messagesRequest = new MessageThreadsIndexRequest(
        perPage: '50',
        lastMessageContent: 'Hello, world!',
        isArchived: true,
        lastMessageId: 'msg_1234567890abcdef',
        fromOrToNumber: '+201234567890',
    );

    $response = Hypersender::sms()->messageThreads($messagesRequest);

    $expectedQuery = $messagesRequest->toQuery();

    Http::assertSent(function (Request $request) use ($expectedQuery) {
        return str_starts_with($request->url(), "{$this->endpoint}/{$this->instanceId}/message-threads")
            && $request->data() === $expectedQuery;
    });

    expect($response->json())->toBe($payload);
});

it('can edit message thread', function () {
    $payload = editMessageThreadEndpointPayload();

    Http::fake([
        '*' => Http::response($payload, 200),
    ]);

    $response = Hypersender::sms()->editMessageThread(true, '9df34184-99e5-43a5-9e6d-371de0344bb9');

    Http::assertSent(function (Request $request) {
        return $request->url() === "{$this->endpoint}/{$this->instanceId}/edit-message-thread"
            && $request->data() === [
                'is_archived' => true,
                'message_thread_id' => '9df34184-99e5-43a5-9e6d-371de0344bb9',
            ];
    });

    expect($response->json())->toBe($payload);
});

it('can delete message thread', function () {
    Http::fake([
        '*' => Http::response([], 200),
    ]);

    $response = Hypersender::sms()->deleteMessageThread('9df34184-99e5-43a5-9e6d-371de0344bb9');

    Http::assertSent(function (Request $request) {
        return $request->url() === "{$this->endpoint}/{$this->instanceId}/delete-message-thread?message_thread_id=9df34184-99e5-43a5-9e6d-371de0344bb9"
            && $request->data() === [];
    });

    expect($response->json())->toBe([]);
});

it('can get heartbeats', function () {
    $payload = getHeartbeatsEndpointPayload();

    Http::fake([
        '*' => Http::response($payload, 200),
    ]);

    $response = Hypersender::sms()->getHeartbeats();

    Http::assertSent(function (Request $request) {
        return str_starts_with($request->url(), "{$this->endpoint}/{$this->instanceId}/heartbeats")
            && $request->data() === [];
    });

    expect($response->json())->toBe($payload);
});
