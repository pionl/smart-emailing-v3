<?php
namespace SmartEmailing\v3\Request\CustomFields;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Request\CustomFields\Requests\CreateRequest;
use SmartEmailing\v3\Request\CustomFields\Responses\Response;

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

    /**
     * Creates a request and sends it
     *
     * @param CustomField $customField
     *
     * @return Response
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

}