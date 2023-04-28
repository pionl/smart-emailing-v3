<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Request\Import;

use SmartEmailing\v3\Models\Model;

/**
 * Class SilencePeriod
 *
 * SilencePeriod for Campaign.
 */
class SilencePeriod extends Model
{
    //region Properties
    /**
     * Period unit
     *
     * Default value: days
     */
    private string $unit = 'days';

    /**
     * Period value, must be integer
     *
     * Default value: 1
     */
    private int $value = 1;

    //endregion

    //region Setters
    /**
     * SilencePeriod constructor.
     *
     * @param string        $unit
     * @param int           $value
     */
    public function __construct($unit, $value)
    {
        $this->unit = $unit;
        $this->value = $value;
    }

    //endregion

    /**
     * Converts the settings to array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'unit' => $this->unit,
            'value' => $this->value,
        ];
    }

    public function jsonSerialize(): array
    {
        // Don't remove any null/empty array - not needed
        return $this->toArray();
    }
}
