<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Contacts\Get;

use SmartEmailing\v3\Endpoints\AbstractItemResponse;
use SmartEmailing\v3\Models\Contact;
use SmartEmailing\v3\Models\Model;

/**
 * @extends AbstractItemResponse<Contact>
 */
class ContactsGetResponse extends AbstractItemResponse
{
    protected function createDataItem(\stdClass $dataItem): Model
    {
        return Contact::fromJSON($dataItem);
    }
}
