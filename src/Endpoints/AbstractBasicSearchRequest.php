<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints;

use SmartEmailing\v3\Api;

/**
 * @template TResponse of AbstractResponse
 * @extends AbstractRequest<TResponse>
 */
abstract class AbstractBasicSearchRequest extends AbstractRequest
{
    public ?array $select = null;

    private int $page = 1;

    private int $limit = 100;

    /**
     * @param int|null $page  desired page
     * @param int|null $limit Number of records on page. Maximum (default) allowed value is 500
     */
    public function __construct(Api $api, ?int $page = null, ?int $limit = null)
    {
        parent::__construct($api);
        $this->page = $page ?? 1;
        $this->limit = $limit ?? 100;
    }

    /**
     * List of properties to select
     *
     * @return $this
     */
    public function select(array $select): self
    {
        $this->select = $select;
        return $this;
    }

    /**
     * @return $this
     */
    public function setPage(int $page, int $limit = 100): self
    {
        $this->page = $page;
        $this->limit = $limit;
        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * Converts the limit and page into sql offset
     */
    public function getOffset(): int
    {
        return ($this->page - 1) * $this->limit;
    }

    /**
     * Builds a GET query
     */
    public function query(): array
    {
        $query = [
            'limit' => $this->getLimit(),
            'offset' => $this->getOffset(),
        ];

        // Append the optional filters/setup
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

    /**
     * Sets the value into array if not valid
     *
     * @param mixed $value
     */
    protected function setQuery(array &$array, string $key, $value): void
    {
        if ($value === null) {
            return;
        }

        $array[$key] = is_array($value) ? implode(',', $value) : $value;
    }
}
