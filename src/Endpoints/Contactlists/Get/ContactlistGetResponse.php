<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Contactlists\Get;

use SmartEmailing\v3\Endpoints\AbstractItemResponse;
use SmartEmailing\v3\Models\Contactlist;
use SmartEmailing\v3\Models\Model;

/**
 * @extends AbstractItemResponse<Contactlist>
 */
class ContactlistGetResponse extends AbstractItemResponse
{
    protected function createDataItem($dataItem): Model
    {
        return Contactlist::fromJSON($dataItem);
    }
}
