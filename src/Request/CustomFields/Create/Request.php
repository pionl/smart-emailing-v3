<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Request\CustomFields\Create;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Request\AbstractRequest;
use SmartEmailing\v3\Request\CustomFields\CustomField;

/**
 * @extends AbstractRequest<Response>
 */
class Request extends AbstractRequest
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

    protected function endpoint(): string
    {
        return 'customfields';
    }

    protected function options()
    {
        PropertyRequiredException::throwIf('customField', is_object($this->customField));

        return [
            'json' => $this->customField->jsonSerialize(),
        ];
    }

    protected function method(): string
    {
        return 'POST';
    }

    protected function createResponse($response)
    {
        return new Response($response);
    }
}
