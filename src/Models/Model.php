<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

abstract class Model implements \JsonSerializable
{
    /**
     * Copies the data from the JSON
     *
     * @param \stdClass $json
     *
     * @return static
     */
    public static function fromJSON($json)
    {
        $item = new static(); /** @phpstan-ignore-line */

        // Get all the data that is supported and try to
        // get it from the json with same key
        foreach (array_keys($item->toArray()) as $key) {
            if (property_exists($json, $key)) {
                $item->{$key} = $json->{$key};
            }
        }

        return $item;
    }

    /**
     * Returns the full representation of the model data (even empty values)
     *
     * @return array
     */
    abstract public function toArray();

    /**
     * Returns items in array with removed null/empty array values
     */
    public function jsonSerialize(): array
    {
        return array_filter($this->toArray(), static function ($val) {
            // Don`t show empty array
            if (is_array($val)) {
                return $val !== [];
            } elseif ($val instanceof AbstractHolder) {
                return $val->isEmpty() === false;
            }

            return $val !== null;
        });
    }
}
