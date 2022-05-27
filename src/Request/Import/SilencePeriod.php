<?php
namespace SmartEmailing\v3\Request\Import;

use SmartEmailing\v3\Models\Model;

/**
 * Class SilencePeriod
 *
 * SilencePeriod for Campaign.
 *
 * @package SmartEmailing\v3\Request\Import
 */
class SilencePeriod extends Model
{
    //region Properties
    /**
     * Period unit
     *
     * Default value: days
     * @var string
     */
    private $unit = 'days';
    /**
     * Period value, must be integer
     *
     * Default value: 1
     * @var int
     */
    private $value = 1;
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
     * @return array
     */
    public function toArray()
    {
        return [
            'unit' => $this->unit,
            'value' => $this->value
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        // Don't remove any null/empty array - not needed
        return $this->toArray();
    }

}
