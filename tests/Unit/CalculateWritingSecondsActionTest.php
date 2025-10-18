<?php

use Hypersender\Actions\CalculateWritingSecondsAction;
use Illuminate\Validation\ValidationException;

it('caps calculated seconds at max_seconds', function () {
    $longText = str_repeat('This is a fairly long message to simulate typing. ', 30);

    $seconds = app(CalculateWritingSecondsAction::class, [
        'text' => $longText,
        'maxSeconds' => 8,
    ])->execute();

    expect($seconds)
        ->toBeInt()
        ->and($seconds)->toBeGreaterThan(0)
        ->and($seconds)->toBeLessThanOrEqual(8);
});

it('returns a reasonable range for short text without max', function () {
    $seconds = app(CalculateWritingSecondsAction::class, [
        'text' => 'Hi there',
    ])->execute();

    // Given algorithm randomness and thinking time, keep bounds loose but meaningful
    expect($seconds)
        ->toBeInt()
        ->and($seconds)->toBeGreaterThanOrEqual(2)
        ->and($seconds)->toBeLessThanOrEqual(10);
});

it('validates that text is required', function () {
    app(CalculateWritingSecondsAction::class, ['text' => ''])
        ->execute();
})->throws(ValidationException::class);
