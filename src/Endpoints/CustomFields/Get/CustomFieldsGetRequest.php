<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\CustomFields\Get;

use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Endpoints\AbstractGetRequest;
use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Models\CustomFieldDefinition;

/**
 * @extends AbstractGetRequest<CustomFieldsGetResponse>
 */
class CustomFieldsGetRequest extends AbstractGetRequest
{
    public function select(array $select): self
    {
        InvalidFormatException::checkAllowedValues($select, CustomFieldDefinition::SELECT_FIELDS);
        return parent::select($select);
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
