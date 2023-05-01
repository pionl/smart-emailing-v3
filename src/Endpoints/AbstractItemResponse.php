<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints;

use SmartEmailing\v3\Models\Model;

/**
 * @template TData of Model
 * @extends AbstractDataResponse<TData>
 */
abstract class AbstractItemResponse extends AbstractDataResponse
{
    protected function setupData()
    {
        parent::setupData();

        $data = $this->value($this->json, 'data');
        if ($data !== null) {
            $this->data = $this->createDataItem($data);
        }

        return $this;
    }

    abstract protected function createDataItem($data): Model;
}
