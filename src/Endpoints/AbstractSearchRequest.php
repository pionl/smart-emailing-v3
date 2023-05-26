<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints;

/**
 * @template TResponse of AbstractResponse
 * @template TFilter of AbstractFilters
 * @extends AbstractBasicSearchRequest<TResponse>
 */
abstract class AbstractSearchRequest extends AbstractBasicSearchRequest
{
    public ?array $expand = null;

    public ?array $sort = null;

    /**
     * @var TFilter|null
     */
    protected ?AbstractFilters $filters = null;

    /**
     * Current filters
     *
     * @return TFilter
     */
    public function filter(): AbstractFilters
    {
        if ($this->filters === null) {
            $this->filters = $this->createFilters();
        }
        return $this->filters;
    }

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
     * List of sorting keys from left side. Prepend "-" to any key for desc direction
     *
     * @param string|string[] $sort
     * @return $this
     */
    public function sortBy($sort): self
    {
        if (is_array($sort) === false) {
            $sort = [$sort];
        }
        $this->sort = $sort;
        return $this;
    }

    /**
     * Builds a GET query
     */
    public function query(): array
    {
        $query = parent::query();

        // Append the optional filters/setup
        $this->setQuery($query, 'sort', $this->sort);
        $this->setQuery($query, 'expand', $this->expand);

        return array_merge($query, $this->filter()->toArray());
    }

    /**
     * @return TFilter
     */
    abstract protected function createFilters(): AbstractFilters;
}
