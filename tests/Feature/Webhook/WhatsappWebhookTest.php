<?php

use Hypersender\Hypersender\Events\PresenceUpdate;
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
