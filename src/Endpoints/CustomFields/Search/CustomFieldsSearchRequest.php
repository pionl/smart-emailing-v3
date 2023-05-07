<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\CustomFields\Search;

use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Api;
use SmartEmailing\v3\Endpoints\AbstractRequest;
use SmartEmailing\v3\Exceptions\InvalidFormatException;

/**
 * @extends AbstractRequest<CustomFieldsSearchResponse>
 */
class CustomFieldsSearchRequest extends AbstractRequest
{
    public int $page;

    public int $limit;

    /**
     * Comma separated list of properties to select. eg. "?select=id,name" If not provided, all fields are selected.
     *
     * Allowed values: "id", "name", "type"
     */
    public ?string $select = null;

    /**
     * Using this parameter, "customfield_options_url" property will be replaced by "customfield_options" contianing
     * expanded data. See examples below For more information see "/customfield-options" endpoint.
     *
     * Allowed values: "customfield_options"
     */
    public ?string $expand = null;

    /**
     * Comma separated list of sorting keys from left side. Prepend "-" to any key for desc direction, eg.
     * "?sort=type,-name"
     *
     * Allowed values: "id", "name", "type"
     */
    public ?string $sort = null;

    /**
     * Filters holder object
     */
    protected CustomFieldsSearchFilters $filters;

    /**
     * @param int|null $page  desired page
     * @param int|null $limit Number of records on page. Maximum (default) allowed value is 500
     */
    public function __construct(Api $api, ?int $page = null, ?int $limit = null)
    {
        parent::__construct($api);

        $this->page = $page ?? 1;
        $this->limit = $limit ?? 100;
        $this->filters = new CustomFieldsSearchFilters($this);
    }

    /**
     * Current filters
     */
    public function filter(): CustomFieldsSearchFilters
    {
        return $this->filters;
    }

    /**
     * Using this parameter, "customfield_options_url" property will be replaced by "customfield_options" contianing
     * expanded data. See examples below For more information see "/customfield-options" endpoint.
     *
     * Allowed values: "customfield_options"
     */
    public function expandBy(string $expand): self
    {
        InvalidFormatException::checkInArray($expand, ['customfield_options']);
        $this->expand = $expand;
        return $this;
    }

    /**
     * Comma separated list of properties to select. eg. "?select=id,name" If not provided, all fields are selected.
     *
     * Allowed values: "id", "name", "type"
     */
    public function select(?string $select): self
    {
        $this->select = $select;
        return $this;
    }

    /**
     * Comma separated list of sorting keys from left side. Prepend "-" to any key for desc direction, eg.
     * "?sort=type,-name"
     *
     * Allowed values: "id", "name", "type"
     */
    public function sortBy(?string $sort): self
    {
        $this->sort = $sort;
        return $this;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Converts the limit and page into sql offset
     */
    public function offset(): int
    {
        return ($this->page - 1) * $this->limit;
    }

    /**
     * Builds a GET query
     */
    public function query(): array
    {
        $query = [
            'limit' => $this->limit,
            'offset' => $this->offset(),
        ];

        // Append the optional filters/setup
        $this->setIfNotNull($query, 'sort', $this->sort)
            ->setIfNotNull($query, 'select', $this->select)
            ->setIfNotNull($query, 'id', $this->filter()->id)
            ->setIfNotNull($query, 'name', $this->filter()->name)
            ->setIfNotNull($query, 'type', $this->filter()->type)
            ->setIfNotNull($query, 'expand', $this->expand);

        return $query;
    }

    public function toArray(): array
    {
        return [];
    }

    protected function endpoint(): string
    {
        return 'customfields';
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

    protected function createResponse(?ResponseInterface $response): CustomFieldsSearchResponse
    {
        return new CustomFieldsSearchResponse($response);
    }

    /**
     * Sets the value into array if not valid
     *
     * @param mixed $value
     *
     * @return $this
     */
    protected function setIfNotNull(array &$array, string $key, $value)
    {
        if ($value === null) {
            return $this;
        }

        $array[$key] = $value;
        return $this;
    }
}
