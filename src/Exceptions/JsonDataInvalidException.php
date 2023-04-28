<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Exceptions;

class JsonDataInvalidException extends JsonDataMissingException
{
    public function __construct($key, $functionCheck, $code = 500, \Exception $previous = null)
    {
        parent::__construct(sprintf("The JSON value '%s' is invalid - %s", $key, $functionCheck), $code, $previous);
    }

    /**
     * Throws an exception if the key is not in the array
     *
     * @param array|\stdClass $arrayOrObject
     * @param string         $key
     * @param string         $functionCheck run a validation function)
     *
     * @return mixed the value
     */
    public static function throwIfInValid($arrayOrObject, $key, $functionCheck)
    {
        $value = static::throwIfSet($arrayOrObject, $key);

        if (! call_user_func($functionCheck, $value)) {
            throw new self($key, $functionCheck);
        }

        return $value;
    }
}
