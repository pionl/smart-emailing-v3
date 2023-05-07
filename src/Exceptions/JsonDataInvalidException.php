<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Exceptions;

class JsonDataInvalidException extends JsonDataMissingException
{
    public function __construct(string $key, string $functionCheck, ?int $code = 500, \Exception $previous = null)
    {
        parent::__construct(sprintf(
            "The JSON value '%s' is invalid - %s",
            $key,
            $functionCheck
        ), $code ?? 500, $previous);
    }

    /**
     * Throws an exception if the key is not in the array
     *
     * @param array|\stdClass|null $arrayOrObject
     * @param callable-string $functionCheck run a validation function)
     *
     * @return mixed the value
     */
    public static function throwIfInValid($arrayOrObject, string $key, string $functionCheck)
    {
        if ($arrayOrObject === null) {
            throw new self($key, $functionCheck);
        }

        $value = static::throwIfSet($arrayOrObject, $key);

        if (! call_user_func($functionCheck, $value)) {
            throw new self($key, $functionCheck);
        }

        return $value;
    }
}
