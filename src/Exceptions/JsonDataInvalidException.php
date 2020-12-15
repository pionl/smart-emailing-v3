<?php
namespace SmartEmailing\v3\Exceptions;

use Exception;
use stdClass;

class JsonDataInvalidException extends JsonDataMissingException
{
    /**
     * @inheritDoc
     */
    public function __construct($key, $functionCheck, $code = 500, Exception $previous = null)
    {
        parent::__construct("The JSON value '{$key}' is invalid - {$functionCheck}", $code, $previous);
    }

    /**
     * Throws an exception if the key is not in the array
     *
     * @param array|stdClass $arrayOrObject
     * @param string         $key
     * @param string         $functionCheck run a validation function)
     *
     * @return mixed the value
     *
     * @throws JsonDataMissingException
     */
    public static function throwIfInValid($arrayOrObject, $key, $functionCheck)
    {
        $value = static::throwIfSet($arrayOrObject, $key);

        if (!call_user_func($functionCheck, $value)) {
            throw new JsonDataInvalidException($key, $functionCheck);
        }

        return $value;
    }
}
