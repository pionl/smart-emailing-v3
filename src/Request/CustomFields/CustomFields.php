<?php
namespace SmartEmailing\v3\Request\CustomFields;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Request\CustomFields\Create\Request as CreateRequest;
use SmartEmailing\v3\Request\CustomFields\Create\Response as CreateResponse;
use SmartEmailing\v3\Request\CustomFields\Search\Request as SearchRequest;
use SmartEmailing\v3\Request\CustomFields\Search\Response as SearchResponse;

class CustomFields
{
    /**
     * @var Api
     */
    private $api;

    /**
     * CustomFields constructor.
     *
     * @param Api $api
     */
    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    //region Create
    /**
     * Creates a request and sends it
     *
     * @param CustomField $customField
     *
     * @return CreateResponse
     */
    public function create(CustomField $customField)
    {
        return $this->createRequest($customField)->send();
    }

    /**
     * Creates a request
     *
     * @param CustomField|null $customField
     *
     * @return CreateRequest
     */
    public function createRequest(CustomField $customField = null)
    {
        return new CreateRequest($this->api, $customField);
    }
    //endregion

    //region Search
    /**
     * Runs the search request for a list of custom fields
     *
     * @param int|null $page
     * @param int|null $limit Number of records on page. Maximum (default) allowed value is 500
     *
     * @return SearchResponse
     */
    public function search($page = null, $limit = null)
    {
        return $this->searchRequest($page, $limit)->send();
    }

    /**
     * Prepares the search request for a list of custom fields
     *
     * @param int|null $page
     * @param int|null $limit Number of records on page. Maximum (default) allowed value is 500
     *
     * @return SearchRequest
     */
    public function searchRequest($page = null, $limit = null)
    {
        return new SearchRequest($this->api, $page, $limit);
    }
    //endregion

}