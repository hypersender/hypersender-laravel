<?php

use Hypersender\Hypersender\Events\Sms\MessageCallMissed;
use Hypersender\Hypersender\Events\Sms\MessageNotificationScheduled;
use Hypersender\Hypersender\Events\Sms\MessagePhoneDelivered;
use Hypersender\Hypersender\Events\Sms\MessagePhoneReceived;
use Hypersender\Hypersender\Events\Sms\MessagePhoneSent;
use Hypersender\Hypersender\Events\Sms\MessageSendExpired;
use Hypersender\Hypersender\Events\Sms\MessageSendFailed;
use Hypersender\Hypersender\Events\Sms\PhoneHeartbeatDisabled;
use Hypersender\Hypersender\Events\Sms\PhoneHeartbeatMissed;
use Hypersender\Hypersender\Events\Sms\PhoneHeartbeatOffline;
use Hypersender\Hypersender\Events\Sms\PhoneHeartbeatOnline;
use Hypersender\Hypersender\Events\Sms\PhoneHeartbeatReceived;
use Hypersender\Hypersender\Jobs\ProcessSmsWebhookJob;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;

it('dispatches SMS webhook events', function (\Closure $payloadFactory, string $expectedEventClass) {
    Event::fake();

    $basePayload = $payloadFactory();

    Config::set('hypersender-laravel.sms_webhook_job', ProcessSmsWebhookJob::class);

    $payload = [
        'event' => $basePayload['event'],
        'data' => $basePayload['data'],
    ];

    $route = config('hypersender-laravel.sms_webhook_route');
    $authorizationSecret = config('hypersender-laravel.sms_webhook_authorization_secret');

    $response = $this->postJson("/{$route}", $payload, [
        'authorization' => $authorizationSecret,
    ]);

    $response->assertOk();

    Event::assertDispatched($expectedEventClass, function ($event) use ($payload) {
        return $event->payload === $payload;
    });
})->with([
    'PhoneHeartbeatDisabled' => [fn () => heartbeatDisabledWebhookPayload(), PhoneHeartbeatDisabled::class],
    'PhoneHeartbeatMissed' => [fn () => heartbeatMissedWebhookPayload(), PhoneHeartbeatMissed::class],
    'PhoneHeartbeatOffline' => [fn () => heartbeatOfflineWebhookPayload(), PhoneHeartbeatOffline::class],
    'PhoneHeartbeatOnline' => [fn () => heartbeatOnlineWebhookPayload(), PhoneHeartbeatOnline::class],
    'PhoneHeartbeatReceived' => [fn () => heartbeatReceivedWebhookPayload(), PhoneHeartbeatReceived::class],
    'MessageCallMissed' => [fn () => messageCallMissedWebhookPayload(), MessageCallMissed::class],
    'MessagePhoneDelivered' => [fn () => messageDeliveredWebhookPayload(), MessagePhoneDelivered::class],
    'MessageSendExpired' => [fn () => messageExpiredWebhookPayload(), MessageSendExpired::class],
    'MessageSendFailed' => [fn () => messageFailedWebhookPayload(), MessageSendFailed::class],
    'MessagePhoneReceived' => [fn () => messageReceivedWebhookPayload(), MessagePhoneReceived::class],
    'MessageNotificationScheduled' => [fn () => messageScheduledWebhookPayload(), MessageNotificationScheduled::class],
    'MessagePhoneSent' => [fn () => messageSentWebhookPayload(), MessagePhoneSent::class],
]);
