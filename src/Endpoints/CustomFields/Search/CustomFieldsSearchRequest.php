<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\CustomFields\Search;

use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Endpoints\AbstractSearchRequest;
use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Models\CustomFieldDefinition;

/**
 * @extends AbstractSearchRequest<CustomFieldsSearchResponse, CustomFieldsSearchFilters>
 */
class CustomFieldsSearchRequest extends AbstractSearchRequest
{
    /**
     * Using this parameter, "customfield_options_url" property will be replaced by "customfield_options" contianing
     * expanded data. See examples below For more information see "/customfield-options" endpoint.
     *
     * Allowed values: "customfield_options"
     */
    public function expandBy(array $expand): self
    {
        InvalidFormatException::checkAllowedValues($expand, CustomFieldDefinition::EXPAND_FIELDS);
        return parent::expandBy($expand);
    }

    public function expandCustomFieldOptions(): self
    {
        return $this->expandBy(['customfield_options']);
    }

    /**
     * Comma separated list of properties to select. eg. "?select=id,name" If not provided, all fields are selected.
     *
     * Allowed values: "id", "name", "type"
     */
    public function select(array $select): self
    {
        InvalidFormatException::checkAllowedValues($select, CustomFieldDefinition::SELECT_FIELDS);
        return parent::select($select);
    }

    /**
     * Comma separated list of sorting keys from left side. Prepend "-" to any key for desc direction, eg.
     * "?sort=type,-name"
     *
     * Allowed values: "id", "name", "type", "-id", "-name", "-type"
     *
     * @param string|string[] $sort
     */
    public function sortBy($sort): self
    {
        InvalidFormatException::checkAllowedSortValues($sort, CustomFieldDefinition::SORT_FIELDS);
        return parent::sortBy($sort);
    }

    protected function createFilters(): CustomFieldsSearchFilters
    {
        return new CustomFieldsSearchFilters();
    }

    protected function endpoint(): string
    {
        return 'customfields';
    }

    protected function createResponse(?ResponseInterface $response): CustomFieldsSearchResponse
    {
        return new CustomFieldsSearchResponse($response);
    }
}
