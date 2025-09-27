<?php

use Hypersender\Hypersender\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

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
