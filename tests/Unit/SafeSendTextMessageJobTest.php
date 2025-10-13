<?php

use Hypersender\Hypersender\Facades\Hypersender;
use Hypersender\Hypersender\Jobs\SafeSendTextMessageJob;
use Hypersender\Hypersender\Services\HypersenderWhatsappService;
use Illuminate\Http\Client\Response as HttpResponse;
use Illuminate\Support\Sleep;

function makePayload(array $overrides = []): array
{
    return array_merge([
        'chat_id' => 'chat-123',
        'text' => 'Hello from test',
        'reply_to' => null,
        'link_preview' => null,
        'link_preview_high_quality' => null,
    ], $overrides);
}

it('sends text and respects typing delay', function () {
    Sleep::fake();

    $service = Mockery::mock(HypersenderWhatsappService::class);

    // whatsapp() is called 4 times: readChat, startTyping, sendText, stopTyping
    Hypersender::shouldReceive('whatsapp')->times(4)->andReturn($service);

    $payload = makePayload();

    $ok = Mockery::mock(HttpResponse::class);
    $ok->shouldReceive('successful')->andReturnTrue();
    $ok->shouldReceive('body')->andReturn(json_encode([]));

    $service->shouldReceive('readChat')->once()->with($payload['chat_id'])
        ->andReturn($ok);

    $service->shouldReceive('startTyping')->once()->with($payload['chat_id'])
        ->andReturn($ok);

    $service->shouldReceive('sendText')->once()->withArgs(function ($chatId, $text) use ($payload) {
        return $chatId === $payload['chat_id'] && $text === $payload['text'];
    })->andReturn($ok);

    $service->shouldReceive('stopTyping')->once()->with($payload['chat_id'])
        ->andReturn($ok);

    app(SafeSendTextMessageJob::class, ['payload' => $payload])->handle();

    // Assert exactly one sleep occurred and the interval is greater than 0 and less than or equal to 8 seconds due to max_seconds cap
    Sleep::assertSlept(fn ($interval) => (int) $interval->totalSeconds > 0 && (int) $interval->totalSeconds <= 8, 1);
});

it('fails early if startTyping is unsuccessful', function () {
    Sleep::fake();

    $service = Mockery::mock(HypersenderWhatsappService::class);

    // whatsapp() is called at least twice: readChat, startTyping
    Hypersender::shouldReceive('whatsapp')->times(2)->andReturn($service);

    $payload = makePayload();

    $ok = Mockery::mock(HttpResponse::class);
    $ok->shouldReceive('successful')->andReturnTrue();
    $ok->shouldReceive('body')->andReturn(json_encode([]));
    $service->shouldReceive('readChat')->once()->with($payload['chat_id'])
        ->andReturn($ok);

    $bad = Mockery::mock(HttpResponse::class);
    $bad->shouldReceive('successful')->andReturnFalse();
    $bad->shouldReceive('body')->andReturn(json_encode(sendTextFailurePayload()));
    $service->shouldReceive('startTyping')->once()->with($payload['chat_id'])
        ->andReturn($bad);

    // These should not be called
    $service->shouldReceive('sendText')->never();
    $service->shouldReceive('stopTyping')->never();

    // Execute and ensure no exception escapes
    app(SafeSendTextMessageJob::class, ['payload' => $payload])->handle();

    Sleep::assertNeverSlept();
});

it('does not stop typing when sendText fails', function () {
    Sleep::fake();

    $service = Mockery::mock(HypersenderWhatsappService::class);

    // whatsapp() is called 3 times here: readChat, startTyping, sendText (stopTyping should be skipped)
    Hypersender::shouldReceive('whatsapp')->times(3)->andReturn($service);

    $payload = makePayload();

    $ok = Mockery::mock(HttpResponse::class);
    $ok->shouldReceive('successful')->andReturnTrue();
    $ok->shouldReceive('body')->andReturn(json_encode([]));
    $service->shouldReceive('readChat')->once()->with($payload['chat_id'])
        ->andReturn($ok);

    $service->shouldReceive('startTyping')->once()->with($payload['chat_id'])
        ->andReturn($ok);

    $bad = Mockery::mock(HttpResponse::class);
    $bad->shouldReceive('successful')->andReturnFalse();
    $bad->shouldReceive('body')->andReturn(json_encode(sendTextFailurePayload()));

    $service->shouldReceive('sendText')->once()->withArgs(function ($chatId, $text) use ($payload) {
        return $chatId === $payload['chat_id'] && $text === $payload['text'];
    })->andReturn($bad);

    // Should not be called on failure
    $service->shouldReceive('stopTyping')->never();

    app(SafeSendTextMessageJob::class, ['payload' => $payload])->handle();

    // Sleep should have occurred before sendText
    Sleep::assertSlept(fn ($interval) => (int) $interval->totalSeconds > 0 && (int) $interval->totalSeconds <= 8, 1);
});
