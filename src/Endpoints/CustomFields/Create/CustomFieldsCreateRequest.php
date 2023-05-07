<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\CustomFields\Create;

use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Api;
use SmartEmailing\v3\Endpoints\AbstractRequest;
use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Models\CustomFieldDefinition;

/**
 * @extends AbstractRequest<CustomFieldsCreateResponse>
 */
class CustomFieldsCreateRequest extends AbstractRequest
{
    protected ?CustomFieldDefinition $customField;

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

    public function customField(): ?CustomFieldDefinition
    {
        return $this->customField;
    }

    public function toArray(): array
    {
        return $this->customField !== null ? $this->customField->jsonSerialize() : [];
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

    protected function createResponse(?ResponseInterface $response): CustomFieldsCreateResponse
    {
        return new CustomFieldsCreateResponse($response);
    }
}
