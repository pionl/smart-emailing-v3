<?php
namespace SmartEmailing\v3\Request\CustomFields\Requests;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Request\AbstractRequest;
use SmartEmailing\v3\Request\CustomFields\CustomField;
use SmartEmailing\v3\Request\CustomFields\Responses\Response;

/**
 * Class CreateRequest
 * @package SmartEmailing\v3\Request\CustomFields\Requests
 */
class CreateRequest extends AbstractRequest
{
    /**
     * @var CustomField
     */
    protected $customField;

    public function __construct(Api $api, CustomField $customField = null)
    {
        parent::__construct($api);
        $this->customField = $customField;
    }

    /**
     * Sets the custom field
     *
     * @param CustomField $customField
     *
     * @return $this
     */
    public function setCustomField(CustomField $customField)
    {
        $this->customField = $customField;
        return $this;
    }

    /**
     * @return CustomField|null
     */
    public function customField()
    {
        return $this->customField;
    }

    protected function endpoint()
    {
        return 'customfields';
    }

    protected function options()
    {
        PropertyRequiredException::throwIf('customField', is_object($this->customField));

        return [
            'json' => $this->customField->jsonSerialize()
        ];
    }

    protected function method()
    {
        return 'POST';
    }

    /**
     * @inheritDoc
     *
     * @return Response
     */
    public function send()
    {
        return parent::send();
    }


    /**
     * @inheritDoc
     */
    protected function createResponse($response)
    {
        return new Response($response);
    }


}