<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\CustomFields;

use SmartEmailing\v3\Endpoints\AbstractEndpoint;
use SmartEmailing\v3\Endpoints\CustomFields\Create\CustomFieldsCreateRequest;
use SmartEmailing\v3\Endpoints\CustomFields\Get\CustomFieldsGetRequest;
use SmartEmailing\v3\Endpoints\CustomFields\Search\CustomFieldsSearchRequest;
use SmartEmailing\v3\Models\CustomFieldDefinition;

class CustomFieldsEndpoint extends AbstractEndpoint
{
    public function createRequest(CustomFieldDefinition $customField = null): CustomFieldsCreateRequest
    {
        return new CustomFieldsCreateRequest($this->api, $customField);
    }

    public function create(CustomFieldDefinition $customField): CustomFieldDefinition
    {
        return $this->createRequest($customField)
            ->send()
            ->data();
    }

    /**
     * Runs query for list of custom fields
     *
     * @return CustomFieldDefinition[]
     */
    public function list(int $page = null, int $limit = null): array
    {
        return $this->searchRequest($page, $limit)
            ->send()
            ->data();
    }

    /**
     * Prepares the search request for a list of custom fields
     */
    public function searchRequest(int $page = null, int $limit = null): CustomFieldsSearchRequest
    {
        return new CustomFieldsSearchRequest($this->api, $page, $limit);
    }

    /**
     * Runs a search query for given name and checks if it exists.
     */
    public function getByName(string $name): ?CustomFieldDefinition
    {
        $request = $this->searchRequest();
        $request->filter()
            ->byName(trim($name));
        return $request->send()
            ->getByName(trim($name));
    }

    /**
     * Runs a search query for given name and checks if it exists.
     */
    public function get(int $fieldId): ?CustomFieldDefinition
    {
        return $this->getRequest($fieldId)
            ->send()
            ->data();
    }

    /**
     * Runs a search query for given name and checks if it exists.
     */
    public function getRequest(int $fieldId): CustomFieldsGetRequest
    {
        return new CustomFieldsGetRequest($this->api, $fieldId);
    }
}
