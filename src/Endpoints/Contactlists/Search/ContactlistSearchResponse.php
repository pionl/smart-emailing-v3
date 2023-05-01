<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Contactlists\Search;

use SmartEmailing\v3\Endpoints\AbstractCollectionResponse;
use SmartEmailing\v3\Models\Contactlist;
use SmartEmailing\v3\Models\Model;

/**
 * @extends AbstractCollectionResponse<Contactlist>
 */
class ContactlistSearchResponse extends AbstractCollectionResponse
{
    protected function createDataItem($dataItem): Model
    {
        return Contactlist::fromJSON($dataItem);
    }
}
