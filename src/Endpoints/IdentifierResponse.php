<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints;

use SmartEmailing\v3\Exceptions\PropertyRequiredException;

/**
 * @template TData of \stdClass
 * @extends AbstractDataResponse<TData>
 */
class IdentifierResponse extends AbstractDataResponse
{
    public function identifier(): int
    {
        $data = $this->data();
        if (isset($data->id) === false) {
            throw new PropertyRequiredException('id');
        }
        return $data->id;
    }
}
