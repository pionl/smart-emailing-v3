<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Exceptions;

class PropertyRequiredException extends \LogicException
{
    public function __construct(
        string $propertyName,
        ?string $customMessage = null,
        ?int $code = 500,
        \Exception $previous = null
    ) {
        $message = $customMessage ?? sprintf('Property %s is required to be set', $propertyName);
        parent::__construct($message, $code ?? 500, $previous);
    }

    /**
     * Throws PropertyRequiredException if $condition is false
     */
    public static function throwIf(string $propertyName, bool $condition, ?string $customMessage = null): void
    {
        if ($condition === false) {
            throw new self($propertyName, $customMessage);
        }
    }
}
