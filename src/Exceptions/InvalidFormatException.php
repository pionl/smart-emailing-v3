<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Exceptions;

class InvalidFormatException extends \LogicException
{
    /**
     * Checks if the value is in the array. Throws exception if not
     *
     * @param mixed $value
     */
    public static function checkInArray($value, array $allowed): void
    {
        if (in_array($value, $allowed, true) === false) {
            throw new self(sprintf("Value '%s' not allowed: ", $value) . implode(', ', $allowed));
        }
    }

    public static function checkAllowedValues(array $values, array $allowed): void
    {
        $invalidFields = array_diff($values, $allowed);

        if ($invalidFields !== []) {
            throw new self('These values are not allowed: ' . implode(', ', $invalidFields));
        }
    }

    /**
     * @param string|string[] $values
     */
    public static function checkAllowedSortValues($values, array $allowed): void
    {
        if (is_array($values) === false) {
            $values = [$values];
        }
        foreach ($allowed as $item) {
            $allowed[] = '-' . $item;
        }
        self::checkAllowedValues($values, $allowed);
    }
}
