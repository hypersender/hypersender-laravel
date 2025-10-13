<?php

namespace Hypersender\Hypersender\Actions;

use Hypersender\Hypersender\Support\BaseAction;

/**
 * @property string $text
 * @property int|null $max_seconds
 */
class CalculateWritingSecondsAction extends BaseAction
{
    protected function rules(): array
    {
        return [
            'text' => ['required', 'string'],
            'max_seconds' => ['nullable', 'integer'],
        ];
    }

    public function handle(): int
    {
        // Average typing speed on mobile is around 30-40 words per minute
        // Which is roughly 3-4 characters per second
        $charactersPerSecond = 3.5;

        // Calculate time based on message length
        $seconds = ceil(strlen($this->text) / $charactersPerSecond);

        // Add a small random variation (±15%) to make it more realistic
        $randomFactor = mt_rand(85, 115) / 100;
        $seconds = ceil($seconds * $randomFactor);

        // Add a few seconds for thinking/reviewing before sending
        $thinkingTime = min(5, ceil(strlen($this->text) / 100));
        $seconds += $thinkingTime;

        if ($this->max_seconds) {
            // Ensure we don't exceed maximum allowed seconds
            $seconds = min($seconds, $this->max_seconds);
        }

        return $seconds;
    }
}
