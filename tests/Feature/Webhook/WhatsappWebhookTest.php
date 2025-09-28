<?php

use Hypersender\Hypersender\Events\Whatsapp\MessageAck;
use Hypersender\Hypersender\Events\Whatsapp\MessageAny;
use Hypersender\Hypersender\Events\Whatsapp\MessageReaction;
use Hypersender\Hypersender\Events\Whatsapp\MessageRevoked;
use Hypersender\Hypersender\Events\Whatsapp\MessageWaiting;
use Hypersender\Hypersender\Events\Whatsapp\PollVote;
use Hypersender\Hypersender\Events\Whatsapp\PollVoteFailed;
use Hypersender\Hypersender\Events\Whatsapp\PresenceUpdate;
use Hypersender\Hypersender\Jobs\ProcessWhatsappWebhookJob;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;

it('dispatches PresenceUpdate event via webhook', function () {
    Event::fake();

    $payload = presenceUpdateWebhookPayload();

    Config::set('hypersender-laravel.whatsapp_webhook_job', ProcessWhatsappWebhookJob::class);

    $payload = [
        'event' => $payload['event'],
        'data' => $payload['data'],
    ];

    $route = config('hypersender-laravel.whatsapp_webhook_route');
    $signatureHeader = config('hypersender-laravel.whatsapp_webhook_signature_header');
    $secret = 'testsecret';

    $response = $this->postJson("/{$route}", $payload, [
        $signatureHeader => $secret,
    ]);

    $response->assertOk();

    Event::assertDispatched(PresenceUpdate::class, function ($event) use ($payload) {
        return $event->payload === $payload;
    });
});

it('dispatches MessageAny event via webhook', function () {
    Event::fake();

    $payload = messageAnyWebhookPayload();

    Config::set('hypersender-laravel.whatsapp_webhook_job', ProcessWhatsappWebhookJob::class);

    $payload = [
        'event' => $payload['event'],
        'data' => $payload['data'],
    ];

    $route = config('hypersender-laravel.whatsapp_webhook_route');
    $signatureHeader = config('hypersender-laravel.whatsapp_webhook_signature_header');
    $secret = 'testsecret';

    $response = $this->postJson("/{$route}", $payload, [
        $signatureHeader => $secret,
    ]);

    $response->assertOk();

    Event::assertDispatched(MessageAny::class, function ($event) use ($payload) {
        return $event->payload === $payload;
    });
});

it('dispatches MessageReaction event via webhook', function () {
    Event::fake();

    $payload = messageReactionWebhookPayload();

    Config::set('hypersender-laravel.whatsapp_webhook_job', ProcessWhatsappWebhookJob::class);

    $payload = [
        'event' => $payload['event'],
        'data' => $payload['data'],
    ];

    $route = config('hypersender-laravel.whatsapp_webhook_route');
    $signatureHeader = config('hypersender-laravel.whatsapp_webhook_signature_header');
    $secret = 'testsecret';

    $response = $this->postJson("/{$route}", $payload, [
        $signatureHeader => $secret,
    ]);

    $response->assertOk();

    Event::assertDispatched(MessageReaction::class, function ($event) use ($payload) {
        return $event->payload === $payload;
    });
});

it('dispatches MessageAck event via webhook', function () {
    Event::fake();

    $payload = messageAckWebhookPayload();

    Config::set('hypersender-laravel.whatsapp_webhook_job', ProcessWhatsappWebhookJob::class);

    $payload = [
        'event' => $payload['event'],
        'data' => $payload['data'],
    ];

    $route = config('hypersender-laravel.whatsapp_webhook_route');
    $signatureHeader = config('hypersender-laravel.whatsapp_webhook_signature_header');
    $secret = 'testsecret';

    $response = $this->postJson("/{$route}", $payload, [
        $signatureHeader => $secret,
    ]);

    $response->assertOk();

    Event::assertDispatched(MessageAck::class, function ($event) use ($payload) {
        return $event->payload === $payload;
    });
});

it('dispatches MessageWaiting event via webhook', function () {
    Event::fake();

    $payload = messageWaitingWebhookPayload();

    Config::set('hypersender-laravel.whatsapp_webhook_job', ProcessWhatsappWebhookJob::class);

    $payload = [
        'event' => $payload['event'],
        'data' => $payload['data'],
    ];

    $route = config('hypersender-laravel.whatsapp_webhook_route');
    $signatureHeader = config('hypersender-laravel.whatsapp_webhook_signature_header');
    $secret = 'testsecret';

    $response = $this->postJson("/{$route}", $payload, [
        $signatureHeader => $secret,
    ]);

    $response->assertOk();

    Event::assertDispatched(MessageWaiting::class, function ($event) use ($payload) {
        return $event->payload === $payload;
    });
});

it('dispatches MessageRevoked event via webhook', function () {
    Event::fake();

    $payload = messageRevokedWebhookPayload();

    Config::set('hypersender-laravel.whatsapp_webhook_job', ProcessWhatsappWebhookJob::class);

    $payload = [
        'event' => $payload['event'],
        'data' => $payload['data'],
    ];

    $route = config('hypersender-laravel.whatsapp_webhook_route');
    $signatureHeader = config('hypersender-laravel.whatsapp_webhook_signature_header');
    $secret = 'testsecret';

    $response = $this->postJson("/{$route}", $payload, [
        $signatureHeader => $secret,
    ]);

    $response->assertOk();

    Event::assertDispatched(MessageRevoked::class, function ($event) use ($payload) {
        return $event->payload === $payload;
    });
});

it('dispatches PollVote event via webhook', function () {
    Event::fake();

    $payload = pollVoteWebhookPayload();

    Config::set('hypersender-laravel.whatsapp_webhook_job', ProcessWhatsappWebhookJob::class);

    $payload = [
        'event' => $payload['event'],
        'data' => $payload['data'],
    ];

    $route = config('hypersender-laravel.whatsapp_webhook_route');
    $signatureHeader = config('hypersender-laravel.whatsapp_webhook_signature_header');
    $secret = 'testsecret';

    $response = $this->postJson("/{$route}", $payload, [
        $signatureHeader => $secret,
    ]);

    $response->assertOk();

    Event::assertDispatched(PollVote::class, function ($event) use ($payload) {
        return $event->payload === $payload;
    });
});

it('dispatches PollVoteFailed event via webhook', function () {
    Event::fake();

    $payload = pollVoteFailedWebhookPayload();

    Config::set('hypersender-laravel.whatsapp_webhook_job', ProcessWhatsappWebhookJob::class);

    $payload = [
        'event' => $payload['event'],
        'data' => $payload['data'],
    ];

    $route = config('hypersender-laravel.whatsapp_webhook_route');
    $signatureHeader = config('hypersender-laravel.whatsapp_webhook_signature_header');
    $secret = 'testsecret';

    $response = $this->postJson("/{$route}", $payload, [
        $signatureHeader => $secret,
    ]);

    $response->assertOk();

    Event::assertDispatched(PollVoteFailed::class, function ($event) use ($payload) {
        return $event->payload === $payload;
    });
});
