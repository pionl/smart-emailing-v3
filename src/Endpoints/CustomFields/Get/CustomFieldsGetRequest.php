<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\CustomFields\Get;

use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Endpoints\AbstractExpandableGetRequest;
use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Models\CustomFieldDefinition;

/**
 * @extends AbstractExpandableGetRequest<CustomFieldsGetResponse>
 */
class CustomFieldsGetRequest extends AbstractExpandableGetRequest
{
    public function select(array $select): self
    {
        InvalidFormatException::checkAllowedValues($select, CustomFieldDefinition::SELECT_FIELDS);
        return parent::select($select);
    }

    public function expandBy(array $expand): self
    {
        InvalidFormatException::checkAllowedValues($expand, CustomFieldDefinition::EXPAND_FIELDS);
        return parent::expandBy($expand);
    }

    public function expandCustomFieldOptions(): self
    {
        return $this->expandBy(['customfield_options']);
    }

    protected function endpoint(): string
    {
        return 'customfield/' . $this->getItemId();
    }

    protected function createResponse(?ResponseInterface $response): CustomFieldsGetResponse
    {
        return new CustomFieldsGetResponse($response);
    }
}
