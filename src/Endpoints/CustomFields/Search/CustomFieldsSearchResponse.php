<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\CustomFields\Search;

use SmartEmailing\v3\Endpoints\AbstractCollectionResponse;
use SmartEmailing\v3\Models\CustomFieldDefinition;
use SmartEmailing\v3\Models\Model;

/**
 * @extends AbstractCollectionResponse<CustomFieldDefinition>
 */
class CustomFieldsSearchResponse extends AbstractCollectionResponse
{
    public function getByName($name): ?CustomFieldDefinition
    {
        foreach ($this->data() as $item) {
            if (trim($item->name) === $name) {
                return $item;
            }
        }
        return null;
    }

    protected function createDataItem($dataItem): Model
    {
        return CustomFieldDefinition::fromJSON($dataItem);
    }
}
