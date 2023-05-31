<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Contactlists\Create;

use SmartEmailing\v3\Endpoints\AbstractItemResponse;
use SmartEmailing\v3\Models\Contactlist;
use SmartEmailing\v3\Models\Model;

/**
 * @extends AbstractItemResponse<Contactlist>
 */
class ContactlistCreateResponse extends AbstractItemResponse
{
    protected function createDataItem(\stdClass $dataItem): Model
    {
        return Contactlist::fromJSON($dataItem);
    }
}
