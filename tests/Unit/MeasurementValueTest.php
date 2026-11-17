<?php

namespace Tests\Unit;

use App\Support\MeasurementValue;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class MeasurementValueTest extends TestCase
{
    #[DataProvider('numericValues')]
    public function test_recognizes_complete_decimal_values(string $input, float $expected): void
    {
        $this->assertSame($expected, MeasurementValue::toNumber($input));
    }

    public static function numericValues(): array
    {
        return [
            'integer' => ['95', 95.0],
            'zero' => ['0', 0.0],
            'comma' => ['82,40', 82.4],
            'point' => ['82.4', 82.4],
            'negative' => ['-0,5', -0.5],
            'spaces and sign' => [' +12.5 ', 12.5],
            'fraction' => [',5', 0.5],
        ];
    }

    #[DataProvider('nonNumericValues')]
    public function test_does_not_infer_a_number_from_text_or_compound_values(string $input): void
    {
        $this->assertNull(MeasurementValue::toNumber($input));
    }

    public static function nonNumericValues(): array
    {
        return [
            'text' => ['positivo'],
            'compound' => ['120/80'],
            'unit suffix' => ['95 mg/dL'],
            'comparison' => ['< 5'],
            'thousands separators' => ['1.234,56'],
            'internal spaces' => ['1 234'],
            'scientific notation' => ['1e3'],
            'empty' => ['  '],
            'not finite' => [str_repeat('9', 400)],
        ];
    }
}
