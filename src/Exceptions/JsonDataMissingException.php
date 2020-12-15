<?php
namespace SmartEmailing\v3\Exceptions;

use Exception;

class JsonDataMissingException extends Exception
{
    /**
     * @inheritDoc
     */
    public function __construct($key, $code = 500, Exception $previous = null)
    {
        parent::__construct("The JSON response is missing '{$key}' value", $code, $previous);
    }

    /**
     * Throws an exception if the key is not in the array
     *
     * @param array|\stdClass $arrayOrObject
     * @param string          $key
     *
     * @return mixed the value
     *
     * @throws JsonDataMissingException
     */
    public static function throwIfSet($arrayOrObject, $key)
    {
        if (is_array($arrayOrObject)) {
            if (!array_key_exists($key, $arrayOrObject)) {
                throw new JsonDataMissingException($key);
            }

            return $arrayOrObject[$key];
        }

        if (!property_exists($arrayOrObject, $key)) {
            throw new JsonDataMissingException($key);
        }

        return $arrayOrObject->{$key};
    }

}
