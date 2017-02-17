<?php
namespace SmartEmailing\v3\Exceptions;

use Exception;

class PropertyRequiredException extends \LogicException
{
    /**
     * Throws PropertyRequiredException if $condition is false
     * @param string $propertyName
     * @param boolean $condition
     *
     * @throws PropertyRequiredException
     */
    public static function throwIf($propertyName, $condition)
    {
        if (!$condition) {
            throw new PropertyRequiredException($propertyName);
        }
    }

    public function __construct($propertyName, $code = 500, Exception $previous = null)
    {
        parent::__construct("Property {$propertyName} is required to be set", $code, $previous);
    }

}