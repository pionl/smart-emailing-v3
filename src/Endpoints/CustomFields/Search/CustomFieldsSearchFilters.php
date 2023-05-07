<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\CustomFields\Search;

use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Models\CustomFieldDefinition;

class CustomFieldsSearchFilters
{
    /**
     * Filters the results by the name
     */
    public ?string $name = null;

    /**
     * Filters the results by the id
     */
    public ?int $id = null;

    /**
     * Filters the results by the type
     */
    public ?string $type = null;

    protected CustomFieldsSearchRequest $request;

    public function __construct(CustomFieldsSearchRequest $request)
    {
        $this->request = $request;
    }

    public function byName(?string $byName): self
    {
        $this->name = $byName;
        return $this;
    }

    /**
     * @param int|numeric-string $byId
     */
    public function byId($byId): self
    {
        $this->id = (int) $byId;
        return $this;
    }

    public function byType(?string $byType): self
    {
        InvalidFormatException::checkInArray($byType, CustomFieldDefinition::types());
        $this->type = $byType;
        return $this;
    }
}
