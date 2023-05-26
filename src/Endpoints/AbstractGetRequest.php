<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints;

use SmartEmailing\v3\Api;

/**
 * @template TResponse of AbstractItemResponse
 * @extends AbstractRequest<TResponse>
 */
abstract class AbstractGetRequest extends AbstractRequest
{
    /**
     * @var string[]|null
     */
    private ?array $select = null;

    private int $itemId;

    public function __construct(Api $api, int $itemId)
    {
        parent::__construct($api);
        $this->itemId = $itemId;
    }

    public function getItemId(): int
    {
        return $this->itemId;
    }

    /**
     * @return $this
     */
    public function select(array $select): self
    {
        $this->select = array_unique(array_merge($this->select ?? [], $select));
        return $this;
    }

    /**
     * Builds a GET query
     */
    public function query(): array
    {
        $query = parent::query();
        $this->setQuery($query, 'select', $this->select);
        return $query;
    }

    public function toArray(): array
    {
        return [];
    }

    /**
     * @return array{query: mixed[]}
     */
    protected function options(): array
    {
        return [
            'query' => $this->query(),
        ];
    }
}
