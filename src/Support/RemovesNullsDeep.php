<?php

namespace Hypersender\Hypersender\Support;

trait RemovesNullsDeep
{
    /**
     * Recursively remove null values and empty arrays from the given array.
     */
    private static function removeNullsDeep(array $data): array
    {
        $mapped = array_map(
            fn ($value) => is_array($value) ? self::removeNullsDeep($value) : $value,
            $data
        );

        return array_filter(
            $mapped,
            fn ($value) => is_array($value) ? $value !== [] : $value !== null
        );
    }
}
