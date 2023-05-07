<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

use SmartEmailing\v3\Exceptions\InvalidFormatException;

abstract class Model implements \JsonSerializable
{
    /**
     * Copies the data from the JSON
     */
    public static function fromJSON(\stdClass $json): static
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
     */
    abstract public function toArray(): array;

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Returns items in array with removed null/empty array values
     */
    protected function removeEmptyValues(array $data): array
    {
        return array_filter($data, static function ($val): bool {
            // Don`t show empty array
            if (is_array($val)) {
                return $val !== [];
            } elseif ($val instanceof AbstractHolder) {
                return $val->isEmpty() === false;
            }

            return $val !== null;
        });
    }

  protected function convertDate(?string $date, bool $convert): ?string
  {
      if ($convert === false || $date === null) {
          return $date;
      }

      $time = strtotime($date);

      if ($time === false) {
          throw new InvalidFormatException(sprintf('Invalid date format: %s', $date));
      }

      return date('Y-m-d H:i:s', $time);
  }
}
