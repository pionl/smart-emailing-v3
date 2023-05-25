<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Contacts\Get;

use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Endpoints\AbstractGetRequest;
use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Models\Contact;

/**
 * @extends AbstractGetRequest<ContactsGetResponse>
 */
class ContactsGetRequest extends AbstractGetRequest
{
    public function select(array $select): self
    {
        InvalidFormatException::checkAllowedValues($select, Contact::SELECT_FIELDS);
        return parent::select($select);
    }

    protected function endpoint(): string
    {
        return 'contacts/' . $this->getItemId();
    }

    protected function createResponse(?ResponseInterface $response): ContactsGetResponse
    {
        return new ContactsGetResponse($response);
    }
}
