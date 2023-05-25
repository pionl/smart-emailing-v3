<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\CustomFields\Search;

use SmartEmailing\v3\Endpoints\AbstractFilters;
use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Models\CustomFieldDefinition;

class CustomFieldsSearchFilters extends AbstractFilters
{
    /**
     * @param int|numeric-string $byId
     */
    public function byId($byId): self
    {
        $this->filters['id'] = (int) $byId;
        return $this;
    }

    public function byName(?string $byName): self
    {
        $this->filters['name'] = $byName;
        return $this;
    }

    public function byType(?string $byType): self
    {
        InvalidFormatException::checkInArray($byType, CustomFieldDefinition::types());
        $this->filters['type'] = $byType;
        return $this;
    }
}
