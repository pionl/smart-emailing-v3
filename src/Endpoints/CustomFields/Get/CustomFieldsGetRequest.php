<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\CustomFields\Get;

use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Api;
use SmartEmailing\v3\Endpoints\AbstractRequest;
use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Models\CustomFieldDefinition;

/**
 * @extends AbstractRequest<CustomFieldsGetResponse>
 */
class CustomFieldsGetRequest extends AbstractRequest
{
    protected int $fieldId;

    /**
     * @var string[]
     */
    private array $select = CustomFieldDefinition::SELECT_FIELDS;

    public function __construct(Api $api, int $fieldId)
    {
        parent::__construct($api);
        $this->fieldId = $fieldId;
    }

    public function select(array $select): self
    {
        InvalidFormatException::checkAllowedValues($select, CustomFieldDefinition::SELECT_FIELDS);
        $this->select = $select;
        return $this;
    }

    /**
     * @return array{select: string}
     */
    public function toArray(): array
    {
        return [
            'select' => implode(',', $this->select),
        ];
    }

    protected function endpoint(): string
    {
        return 'customfield/' . $this->fieldId;
    }

    protected function createResponse(?ResponseInterface $response): CustomFieldsGetResponse
    {
        return new CustomFieldsGetResponse($response);
    }
}
