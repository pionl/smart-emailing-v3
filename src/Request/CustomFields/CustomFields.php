<?php
namespace SmartEmailing\v3\Request\CustomFields;

use SmartEmailing\v3\Api;

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
     * @return Create\Response
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
     * @return Create\Request
     */
    public function createRequest(CustomField $customField = null)
    {
        return new Create\Request($this->api, $customField);
    }
    //endregion

    //region Search
    /**
     * Runs the search request for a list of custom fields
     *
     * @param int|null $page
     * @param int|null $limit Number of records on page. Maximum (default) allowed value is 500
     *
     * @return Search\Response
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
     * @return Search\Request
     */
    public function searchRequest($page = null, $limit = null)
    {
        return new Search\Request($this->api, $page, $limit);
    }
    //endregion

    /**
     * Runs a search query for given name and checks if it exists.
     *
     * @param string $name
     *
     * @return bool|CustomField
     */
    public function exists($name)
    {
        return (new Exists\Request($this->api, $name))->exists();
    }

}