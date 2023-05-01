<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\CustomFields\Create;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Endpoints\AbstractRequest;
use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Models\CustomFieldDefinition;

/**
 * @extends AbstractRequest<CustomFieldsCreateResponse>
 */
class CustomFieldsCreateRequest extends AbstractRequest
{
    /**
     * @var CustomFieldDefinition
     */
    protected $customField;

    public function __construct(Api $api, CustomFieldDefinition $customField = null)
    {
        parent::__construct($api);
        $this->customField = $customField;
    }

    /**
     * Sets the custom field
     *
     * @return $this
     */
    public function setCustomField(CustomFieldDefinition $customField)
    {
        $this->customField = $customField;
        return $this;
    }

    /**
     * @return CustomFieldDefinition|null
     */
    public function customField()
    {
        return $this->customField;
    }

    public function toArray(): array
    {
        return $this->customField->jsonSerialize();
    }

    protected function endpoint(): string
    {
        return 'customfields';
    }

    protected function options(): array
    {
        PropertyRequiredException::throwIf('customField', is_object($this->customField));
        return parent::options();
    }

    protected function method(): string
    {
        return 'POST';
    }

    protected function createResponse($response)
    {
        return new CustomFieldsCreateResponse($response);
    }
}
