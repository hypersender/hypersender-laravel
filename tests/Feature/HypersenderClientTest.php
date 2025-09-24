<?php

use Hypersender\Hypersender\Facades\Hypersender;
use Illuminate\Support\Facades\Http;

it('can send a text message', function () {
    Http::fake([
        'https://app.hypersender.com/api/whatsapp/v1/send-text' => Http::response(['message' => 'sent'], 200),
    ]);

    $response = Hypersender::sendText('123@c.us', 'Hello', null, true, true);

    expect($response->json())->toBe(['message' => 'sent']);
});

// TODO: Add more tests for the other methods
