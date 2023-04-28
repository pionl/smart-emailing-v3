<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Request\CustomFields\Search;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Request\AbstractRequest;

/**
 * @extends AbstractRequest<Response>
 */
class Request extends AbstractRequest
{
    //region Properties
    /**
     * @var int
     */
    public $page;

    /**
     * @var int
     */
    public $limit;

    /**
     * Comma separated list of properties to select. eg. "?select=id,name" If not provided, all fields are selected.
     *
     * Allowed values: "id", "name", "type"
     *
     * @var string|null
     */
    public $select = null;

    /**
     * Using this parameter, "customfield_options_url" property will be replaced by "customfield_options" contianing
     * expanded data. See examples below For more information see "/customfield-options" endpoint.
     *
     * Allowed values: "customfield_options"
     *
     * @var string
     */
    public $expand = null;

    /**
     * Comma separated list of sorting keys from left side. Prepend "-" to any key for desc direction, eg.
     * "?sort=type,-name"
     *
     * Allowed values: "id", "name", "type"
     *
     * @var string|null
     */
    public $sort = null;

    /**
     * Filters holder object
     *
     * @var Filters
     */
    protected $filters;

    //endregion

    /**
     * SearchRequest constructor.
     *
     * @param int|null $page  desired page
     * @param int|null $limit Number of records on page. Maximum (default) allowed value is 500
     */
    public function __construct(Api $api, $page = null, $limit = null)
    {
        parent::__construct($api);

        $this->page = $page ?? 1;
        $this->limit = $limit ?? 100;
        $this->filters = new Filters($this);
    }

    /**
     * Current filters
     *
     * @return Filters
     */
    public function filter()
    {
        return $this->filters;
    }

    //region Setters
    /**
     * Using this parameter, "customfield_options_url" property will be replaced by "customfield_options" contianing
     * expanded data. See examples below For more information see "/customfield-options" endpoint.
     *
     * Allowed values: "customfield_options"
     *
     * @param string $expand
     *
     * @return Request
     */
    public function expandBy($expand)
    {
        InvalidFormatException::checkInArray($expand, ['customfield_options']);
        $this->expand = $expand;
        return $this;
    }

    /**
     * Comma separated list of properties to select. eg. "?select=id,name" If not provided, all fields are selected.
     *
     * Allowed values: "id", "name", "type"
     *
     * @param null|string $select
     *
     * @return Request
     */
    public function select($select)
    {
        $this->select = $select;
        return $this;
    }

    /**
     * Comma separated list of sorting keys from left side. Prepend "-" to any key for desc direction, eg.
     * "?sort=type,-name"
     *
     * Allowed values: "id", "name", "type"
     *
     * @param null|string $sort
     *
     * @return Request
     */
    public function sortBy($sort)
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * @param int $page
     *
     * @return Request
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @param int $limit
     *
     * @return Request
     */
    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }


    //endregion

    /**
     * Converts the limit and page into sql offset
     *
     * @return int
     */
    public function offset()
    {
        return ($this->page - 1) * $this->limit;
    }

    /**
     * Builds a GET query
     *
     * @return array
     */
    public function query()
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

    protected function endpoint()
    {
        return 'customfields';
    }

    protected function options()
    {
        return [
            'query' => $this->query(),
        ];
    }

    /**
     * @return Response
     */
    protected function createResponse($response)
    {
        return new Response($response);
    }

    /**
     * Sets the value into array if not valid
     *
     * @param string $key
     * @param mixed $value
     *
     * @return $this
     */
    protected function setIfNotNull(array &$array, $key, $value)
    {
        if ($value === null) {
            return $this;
        }

        $array[$key] = $value;
        return $this;
    }
}
