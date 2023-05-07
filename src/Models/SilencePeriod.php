<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

/**
 * SilencePeriod for Campaign.
 */
class SilencePeriod extends Model
{
    /**
     * Period unit
     */
    private string $unit = 'days';

    /**
     * Period value, must be integer
     */
    private int $value = 1;

    public function __construct(string $unit, int $value)
    {
        $this->unit = $unit;
        $this->value = $value;
    }

    /**
     * Converts the settings to array
     *
     * @return array{unit: string, value: int}
     */
    public function toArray(): array
    {
        return [
            'unit' => $this->unit,
            'value' => $this->value,
        ];
    }
}
