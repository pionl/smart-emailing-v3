<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints;

use SmartEmailing\v3\Exceptions\JsonDataInvalidException;
use SmartEmailing\v3\Models\MetaDataInterface;
use SmartEmailing\v3\Models\Model;

/**
 * @template TData of Model
 * @extends AbstractDataResponse<array<TData>>
 */
abstract class AbstractCollectionResponse extends AbstractDataResponse
{
    /**
     * @var \stdClass&MetaDataInterface
     */
    protected ?\stdClass $meta = null;

    /**
     * Response meta list
     *
     * @return \stdClass&MetaDataInterface
     */
    public function meta(): \stdClass
    {
        return $this->meta;
    }

    public function totalCount(): int
    {
        return $this->meta->total_count;
    }

    public function displayedCount(): int
    {
        return $this->meta->displayed_count;
    }

    public function limit(): int
    {
        return $this->meta->limit;
    }

    public function offset(): int
    {
        return $this->meta->offset;
    }

    protected function setupData()
    {
        parent::setupData();
        if ($this->json->meta instanceof \stdClass) {
            $this->set('meta');
        }

        if ($this->isSuccess()) {
            JsonDataInvalidException::throwIfInValid($this->json, 'data', 'is_array');
            JsonDataInvalidException::throwIfInValid($this->json, 'meta', 'is_object');
            $this->data = [];
            foreach ($this->json->data as $dataItem) {
                $this->data[] = $this->createDataItem($dataItem);
            }
            $this->set('meta');
        }

        return $this;
    }

    abstract protected function createDataItem($data): Model;
}
