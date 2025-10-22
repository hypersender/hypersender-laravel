<?php

use Hypersender\Events\Sms\MessageCallMissed;
use Hypersender\Events\Sms\MessageNotificationScheduled;
use Hypersender\Events\Sms\MessagePhoneDelivered;
use Hypersender\Events\Sms\MessagePhoneReceived;
use Hypersender\Events\Sms\MessagePhoneSent;
use Hypersender\Events\Sms\MessageSendExpired;
use Hypersender\Events\Sms\MessageSendFailed;
use Hypersender\Events\Sms\PhoneHeartbeatDisabled;
use Hypersender\Events\Sms\PhoneHeartbeatMissed;
use Hypersender\Events\Sms\PhoneHeartbeatOffline;
use Hypersender\Events\Sms\PhoneHeartbeatOnline;
use Hypersender\Events\Sms\PhoneHeartbeatReceived;
use Hypersender\Jobs\ProcessSmsWebhookJob;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;

it('dispatches SMS webhook events', function (\Closure $payloadFactory, string $expectedEventClass) {
    Event::fake();

    $basePayload = $payloadFactory();

    Config::set('hypersender-config.sms_webhook_job', ProcessSmsWebhookJob::class);

    $payload = [
        'event' => $basePayload['event'],
        'data' => $basePayload['data'],
    ];

    $route = config('hypersender-config.sms_webhook_route');
    $authorizationSecret = config('hypersender-config.sms_webhook_authorization_secret');

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
