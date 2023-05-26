<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints;

/**
 * @template TResponse of AbstractItemResponse
 * @extends AbstractGetRequest<TResponse>
 */
abstract class AbstractExpandableGetRequest extends AbstractGetRequest
{
    public ?array $expand = null;

    /**
     * List of expanded fields
     *
     * @return $this
     */
    public function expandBy(array $expand): self
    {
        $this->expand = array_unique(array_merge($this->expand ?? [], $expand));
        return $this;
    }

    /**
     * Builds a GET query
     */
    public function query(): array
    {
        $query = parent::query();
        $this->setQuery($query, 'expand', $this->expand);
        return $query;
    }
}
