<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Exceptions;

class PropertyRequiredException extends \LogicException
{
    public function __construct($propertyName, $customMessage = null, $code = 500, \Exception $previous = null)
    {
        $message = $customMessage ?? sprintf('Property %s is required to be set', $propertyName);
        parent::__construct($message, $code, $previous);
    }

    /**
     * Throws PropertyRequiredException if $condition is false
     *
     * @param string      $propertyName
     * @param boolean     $condition
     * @param string|null $customMessage
     */
    public static function throwIf($propertyName, $condition, $customMessage = null)
    {
        if ($condition === false) {
            throw new self($propertyName, $customMessage);
        }
    }
}
