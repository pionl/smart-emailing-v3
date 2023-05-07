<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Exceptions;

class JsonDataMissingException extends \Exception
{
    public function __construct(?string $key = null, ?int $code = 500, \Exception $previous = null)
    {
        parent::__construct('The JSON response is missing' . ($key ? sprintf(
            " '%s' value",
            $key
        ) : ''), $code ?? 500, $previous);
    }

    /**
     * Throws an exception if the key is not in the array
     *
     * @param array|\stdClass $arrayOrObject
     *
     * @return mixed the value
     */
    public static function throwIfSet($arrayOrObject, string $key)
    {
        if (is_array($arrayOrObject)) {
            if (array_key_exists($key, $arrayOrObject) === false) {
                throw new self($key);
            }

            return $arrayOrObject[$key];
        }

        if (property_exists($arrayOrObject, $key) === false) {
            throw new self($key);
        }

        return $arrayOrObject->{$key};
    }
}
