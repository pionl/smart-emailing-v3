<?php
namespace SmartEmailing\v3\Models;

use stdClass;

abstract class Model implements \JsonSerializable
{
    /**
     * Copies the data from the JSON
     * @param stdClass $json
     *
     * @return static
     */
    public static function fromJSON($json)
    {
        $item = new static();

        // Get all the data that is supported and try to
        // get it from the json with same key
        foreach ($item->toArray() as $key => $value) {
            if (property_exists($json, $key)) {
                $item->{$key} = $json->{$key};
            }
        }

        return $item;
    }

    /**
     * Returns the full representation of the model data (even empty values)
     * @return array
     */
    abstract public function toArray();

    /**
     * Returns items in array with removed null/empty array values
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_filter($this->toArray(), function ($val) {
            // Don`t show empty array
            if (is_array($val)) {
                return !empty($val);
            } elseif ($val instanceof AbstractHolder) {
                return !$val->isEmpty();
            }

            return !is_null($val);
        });
    }
}
