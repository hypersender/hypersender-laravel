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

it('dispatches WhatsApp webhook events', function (\Closure $payloadFactory, string $expectedEventClass) {
    Event::fake();

    $basePayload = $payloadFactory();

    Config::set('hypersender-laravel.whatsapp_webhook_job', ProcessWhatsappWebhookJob::class);

    $payload = [
        'event' => $basePayload['event'],
        'data' => $basePayload['data'],
    ];

    $route = config('hypersender-laravel.whatsapp_webhook_route');
    $authorization = config('hypersender-laravel.whatsapp_webhook_authorization');
    $authorizationSecret = config('hypersender-laravel.whatsapp_webhook_authorization_secret');

    $response = $this->postJson("/{$route}", $payload, [
        $authorization => $authorizationSecret,
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
