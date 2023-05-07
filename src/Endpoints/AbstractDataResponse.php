<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints;

use SmartEmailing\v3\Exceptions\JsonDataMissingException;
use SmartEmailing\v3\Models\Model;

/**
 * @template TData of array<\stdClass|Model>|\stdClass|Model
 */
class AbstractDataResponse extends AbstractResponse
{
    /**
     * @var TData|null
     */
    protected $data = null;

    /**
     * Response data
     *
     * @return TData
     */
    public function data()
    {
        if ($this->data === null) {
            throw new JsonDataMissingException('data');
        }
        return $this->data;
    }
}
