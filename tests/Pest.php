<?php

use Hypersender\Hypersender\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

// WhatsApp
function sendTextEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/send-text.json');

    return json_decode($payload, true);
}

function sendLinkCustomPreviewPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/send-link-custom-preview.json');

    return json_decode($payload, true);
}

function sendFileEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/send-file.json');

    return json_decode($payload, true);
}

function sendImageEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/send-image.json');

    return json_decode($payload, true);
}

function sendVideoEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/send-video.json');

    return json_decode($payload, true);
}

function sendAudioEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/send-audio.json');

    return json_decode($payload, true);
}

function forwardMessageEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/forward-message.json');

    return json_decode($payload, true);
}

function readChatsEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/read-chats.json');

    return json_decode($payload, true);
}

function sendPollEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/send-poll.json');

    return json_decode($payload, true);
}

function sendLocationEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/send-location.json');

    return json_decode($payload, true);
}

function reactToMessageEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/react-to-message.json');

    return json_decode($payload, true);
}

function deleteMessageEndpointPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/delete-message.json');

    return json_decode($payload, true);
}

// WhatsApp Webhook
function presenceUpdateWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/webhook/presence-update.json');

    return json_decode($payload, true);
}

function messageAnyWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/webhook/message-any.json');

    return json_decode($payload, true);
}

function messageReactionWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/webhook/message-reaction.json');

    return json_decode($payload, true);
}

function messageAckWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/webhook/message-ack.json');

    return json_decode($payload, true);
}

function messageWaitingWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/webhook/message-waiting.json');

    return json_decode($payload, true);
}

function messageRevokedWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/webhook/message-revoked.json');

    return json_decode($payload, true);
}

function pollVoteWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/webhook/poll-vote.json');

    return json_decode($payload, true);
}

function pollVoteFailedWebhookPayload(): array
{
    $payload = file_get_contents(__DIR__.'/fixtures/webhook/poll-vote-failed.json');

    return json_decode($payload, true);
}
