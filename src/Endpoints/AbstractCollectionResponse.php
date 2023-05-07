<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints;

use SmartEmailing\v3\Exceptions\JsonDataInvalidException;
use SmartEmailing\v3\Exceptions\JsonDataMissingException;
use SmartEmailing\v3\Models\MetaDataInterface;
use SmartEmailing\v3\Models\Model;

/**
 * @template TData of Model
 * @extends AbstractDataResponse<array<TData>>
 */
abstract class AbstractCollectionResponse extends AbstractDataResponse
{
    /**
     * @var null|(\stdClass&MetaDataInterface)
     */
    protected ?\stdClass $meta = null;

    /**
     * Response meta list
     *
     * @return \stdClass&MetaDataInterface
     */
    public function meta(): \stdClass
    {
        if ($this->meta === null) {
            throw new \RuntimeException('Meta is not set');
        }
        return $this->meta;
    }

    public function totalCount(): int
    {
        return $this->meta->total_count ?? 0;
    }

    public function displayedCount(): int
    {
        return $this->meta->displayed_count ?? 0;
    }

    public function limit(): int
    {
        return $this->meta->limit ?? 0;
    }

    public function offset(): int
    {
        return $this->meta->offset ?? 0;
    }

    protected function setupData(): self
    {
        parent::setupData();
        if ($this->json instanceof \stdClass === false) {
            throw new JsonDataMissingException();
        }

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

    /**
     * @return TData
     */
    abstract protected function createDataItem(\stdClass $data): Model;
}
