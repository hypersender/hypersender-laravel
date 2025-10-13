<?php

use Hypersender\Hypersender\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

// WhatsApp
function sendTextEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Whatsapp/send-text.json');

    return json_decode($payload, true);
}

function sendLinkCustomPreviewPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Whatsapp/send-link-custom-preview.json');

    return json_decode($payload, true);
}

function sendFileEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Whatsapp/send-file.json');

    return json_decode($payload, true);
}

function sendImageEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Whatsapp/send-image.json');

    return json_decode($payload, true);
}

function sendVideoEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Whatsapp/send-video.json');

    return json_decode($payload, true);
}

function sendAudioEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Whatsapp/send-audio.json');

    return json_decode($payload, true);
}

function forwardMessageEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Whatsapp/forward-message.json');

    return json_decode($payload, true);
}

function readChatsEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Whatsapp/read-chats.json');

    return json_decode($payload, true);
}

function sendPollEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Whatsapp/send-poll.json');

    return json_decode($payload, true);
}

function sendLocationEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Whatsapp/send-location.json');

    return json_decode($payload, true);
}

function reactToMessageEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Whatsapp/react-to-message.json');

    return json_decode($payload, true);
}

function deleteMessageEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Whatsapp/delete-message.json');

    return json_decode($payload, true);
}

function sendTextFailurePayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Whatsapp/send-text-failure.json');

    return json_decode($payload, true);
}

// WhatsApp Webhook
function presenceUpdateWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Whatsapp/Webhook/presence-update.json');

    return json_decode($payload, true);
}

function messageAnyWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Whatsapp/Webhook/message-any.json');

    return json_decode($payload, true);
}

function messageReactionWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Whatsapp/Webhook/message-reaction.json');

    return json_decode($payload, true);
}

function messageAckWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Whatsapp/Webhook/message-ack.json');

    return json_decode($payload, true);
}

function messageWaitingWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Whatsapp/Webhook/message-waiting.json');

    return json_decode($payload, true);
}

function messageRevokedWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Whatsapp/Webhook/message-revoked.json');

    return json_decode($payload, true);
}

function pollVoteWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Whatsapp/Webhook/poll-vote.json');

    return json_decode($payload, true);
}

function pollVoteFailedWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Whatsapp/Webhook/poll-vote-failed.json');

    return json_decode($payload, true);
}

// SMS
function indexMessagesEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Sms/index-messages.json');

    return json_decode($payload, true);
}

function sendSmsMessageEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Sms/send-message.json');

    return json_decode($payload, true);
}

function indexMessageThreadsEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Sms/index-message-threads.json');

    return json_decode($payload, true);
}

function editMessageThreadEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Sms/edit-message-thread.json');

    return json_decode($payload, true);
}

function getHeartbeatsEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Sms/get-heartbeats.json');

    return json_decode($payload, true);
}

// SMS Webhook

function heartbeatDisabledWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Sms/Webhook/heartbeat-disabled.json');

    return json_decode($payload, true);
}

function heartbeatMissedWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Sms/Webhook/heartbeat-missed.json');

    return json_decode($payload, true);
}

function heartbeatOfflineWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Sms/Webhook/heartbeat-offline.json');

    return json_decode($payload, true);
}

function heartbeatOnlineWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Sms/Webhook/heartbeat-online.json');

    return json_decode($payload, true);
}

function heartbeatReceivedWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Sms/Webhook/heartbeat-received.json');

    return json_decode($payload, true);
}

function messageCallMissedWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Sms/Webhook/message-call-missed.json');

    return json_decode($payload, true);
}

function messageDeliveredWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Sms/Webhook/message-delivered.json');

    return json_decode($payload, true);
}

function messageExpiredWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Sms/Webhook/message-expired.json');

    return json_decode($payload, true);
}

function messageFailedWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Sms/Webhook/message-failed.json');

    return json_decode($payload, true);
}

function messageReceivedWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Sms/Webhook/message-received.json');

    return json_decode($payload, true);
}

function messageScheduledWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Sms/Webhook/message-scheduled.json');

    return json_decode($payload, true);
}

function messageSentWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/Sms/Webhook/message-sent.json');

    return json_decode($payload, true);
}
