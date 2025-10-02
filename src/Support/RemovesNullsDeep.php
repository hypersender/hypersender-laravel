<?php

namespace Hypersender\Hypersender\Support;

trait RemovesNullsDeep
{
    /**
     * Recursively remove null values and empty arrays from the given array.
     */
    private static function removeNullsDeep(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::removeNullsDeep($value);
                if ($data[$key] === []) {
                    unset($data[$key]);
                }
            } elseif ($value === null) {
                unset($data[$key]);
            }
        }

        return $data;
    }
}
