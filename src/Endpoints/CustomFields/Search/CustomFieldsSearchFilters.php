<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\CustomFields\Search;

use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Models\CustomFieldDefinition;

class CustomFieldsSearchFilters
{
    /**
     * Filters the results by the name
     *
     * @var string|null
     */
    public $name = null;

    /**
     * Filters the results by the id
     *
     * @var int|null
     */
    public $id = null;

    /**
     * Filters the results by the type
     *
     * @var string|null
     */
    public $type = null;

    /**
     * @var CustomFieldsSearchRequest
     */
    protected $request;

    public function __construct(CustomFieldsSearchRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @param null|string $byName
     *
     * @return CustomFieldsSearchFilters
     */
    public function byName($byName)
    {
        $this->name = $byName;
        return $this;
    }

    /**
     * @param null|int $byId
     *
     * @return CustomFieldsSearchFilters
     */
    public function byId($byId)
    {
        $this->id = $byId;
        return $this;
    }

    /**
     * @param null|string $byType
     *
     * @return CustomFieldsSearchFilters
     */
    public function byType($byType)
    {
        InvalidFormatException::checkInArray($byType, CustomFieldDefinition::types());
        $this->type = $byType;
        return $this;
    }
}
