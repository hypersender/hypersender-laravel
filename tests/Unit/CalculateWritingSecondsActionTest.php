<?php

use Hypersender\Hypersender\Actions\CalculateWritingSecondsAction;
use Illuminate\Validation\ValidationException;

it('caps calculated seconds at max_seconds', function () {
    $longText = str_repeat('This is a fairly long message to simulate typing. ', 30);

    $seconds = CalculateWritingSecondsAction::make([
        'text' => $longText,
        'max_seconds' => 8,
    ])->run();

    expect($seconds)
        ->toBeInt()
        ->and($seconds)->toBeGreaterThan(0)
        ->and($seconds)->toBeLessThanOrEqual(8);
});

it('returns a reasonable range for short text without max', function () {
    $seconds = CalculateWritingSecondsAction::make([
        'text' => 'Hi there',
    ])->run();

    // Given algorithm randomness and thinking time, keep bounds loose but meaningful
    expect($seconds)
        ->toBeInt()
        ->and($seconds)->toBeGreaterThanOrEqual(2)
        ->and($seconds)->toBeLessThanOrEqual(10);
});

it('validates that text is required', function () {
    CalculateWritingSecondsAction::make([])->run();
})->throws(ValidationException::class);
