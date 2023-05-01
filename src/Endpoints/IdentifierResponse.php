<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints;

/**
 * @template TData of \stdClass
 * @extends AbstractDataResponse<TData>
 */
class IdentifierResponse extends AbstractDataResponse
{
    public function identifier(): int
    {
        return $this->data()
->id;
    }
}
