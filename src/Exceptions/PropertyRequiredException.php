<?php
namespace SmartEmailing\v3\Exceptions;

use Exception;

class PropertyRequiredException extends \LogicException
{
    /**
     * Throws PropertyRequiredException if $condition is false
     *
     * @param string      $propertyName
     * @param boolean     $condition
     * @param string|null $customMessage
     */
    public static function throwIf($propertyName, $condition, $customMessage = null)
    {
        if (!$condition) {
            throw new PropertyRequiredException($propertyName, $customMessage);
        }
    }

    public function __construct($propertyName, $customMessage = null, $code = 500, Exception $previous = null)
    {
        $message = is_null($customMessage) ? "Property {$propertyName} is required to be set" : $customMessage;
        parent::__construct($message, $code, $previous);
    }

}
