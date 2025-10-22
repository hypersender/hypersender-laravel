<?php

use Hypersender\Events\Whatsapp\MessageAck;
use Hypersender\Events\Whatsapp\MessageAny;
use Hypersender\Events\Whatsapp\MessageReaction;
use Hypersender\Events\Whatsapp\MessageRevoked;
use Hypersender\Events\Whatsapp\MessageWaiting;
use Hypersender\Events\Whatsapp\PollVote;
use Hypersender\Events\Whatsapp\PollVoteFailed;
use Hypersender\Events\Whatsapp\PresenceUpdate;
use Hypersender\Jobs\ProcessWhatsappWebhookJob;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;

it('dispatches WhatsApp webhook events', function (\Closure $payloadFactory, string $expectedEventClass) {
    Event::fake();

    $basePayload = $payloadFactory();

    Config::set('hypersender-config.whatsapp_webhook_job', ProcessWhatsappWebhookJob::class);

    $payload = [
        'event' => $basePayload['event'],
        'data' => $basePayload['data'],
    ];

    $route = config('hypersender-config.whatsapp_webhook_route');
    $authorizationSecret = config('hypersender-config.whatsapp_webhook_authorization_secret');

    $response = $this->postJson("/{$route}", $payload, [
        'authorization' => $authorizationSecret,
    ]);

    $response->assertOk();

    Event::assertDispatched($expectedEventClass, function ($event) use ($payload) {
        return $event->payload === $payload;
    });
})->with([
    'PresenceUpdate' => [fn () => presenceUpdateWebhookPayload(), PresenceUpdate::class],
    'MessageAny' => [fn () => messageAnyWebhookPayload(), MessageAny::class],
    'MessageReaction' => [fn () => messageReactionWebhookPayload(), MessageReaction::class],
    'MessageAck' => [fn () => messageAckWebhookPayload(), MessageAck::class],
    'MessageWaiting' => [fn () => messageWaitingWebhookPayload(), MessageWaiting::class],
    'MessageRevoked' => [fn () => messageRevokedWebhookPayload(), MessageRevoked::class],
    'PollVote' => [fn () => pollVoteWebhookPayload(), PollVote::class],
    'PollVoteFailed' => [fn () => pollVoteFailedWebhookPayload(), PollVoteFailed::class],
]);
