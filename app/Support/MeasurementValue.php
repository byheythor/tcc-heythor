<?php

namespace App\Support;

final class MeasurementValue
{
    public static function toNumber(string $value): ?float
    {
        $value = trim($value);

        // Accept a whole decimal value, never just the numeric prefix of a text.
        // Thousands separators, units and compound readings are not inferred.
        if (! preg_match('/\A[+-]?(?:\d+(?:[.,]\d+)?|[.,]\d+)\z/', $value)) {
            return null;
        }

        $number = (float) str_replace(',', '.', $value);

        return is_finite($number) ? $number : null;
    }
}
