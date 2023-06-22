<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Contacts\Search;

use SmartEmailing\v3\Endpoints\AbstractCollectionResponse;
use SmartEmailing\v3\Models\Contact;
use SmartEmailing\v3\Models\Model;

/**
 * @extends AbstractCollectionResponse<Contact>
 */
class ContactsSearchResponse extends AbstractCollectionResponse
{
    public function getByEmailAddress(string $email): ?Contact
    {
        foreach ($this->data() as $item) {
            if ($item->getEmailAddress() !== null && strcasecmp($item->getEmailAddress(), $email) === 0) {
                return $item;
            }
        }
        return null;
    }

    protected function createDataItem(\stdClass $dataItem): Model
    {
        return Contact::fromJSON($dataItem);
    }
}
