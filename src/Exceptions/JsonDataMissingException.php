<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Exceptions;

class JsonDataMissingException extends \Exception
{
    public function __construct($key, $code = 500, \Exception $previous = null)
    {
        parent::__construct(sprintf("The JSON response is missing '%s' value", $key), $code, $previous);
    }

    /**
     * Throws an exception if the key is not in the array
     *
     * @param array|\stdClass $arrayOrObject
     * @param string          $key
     *
     * @return mixed the value
     */
    public static function throwIfSet($arrayOrObject, $key)
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
