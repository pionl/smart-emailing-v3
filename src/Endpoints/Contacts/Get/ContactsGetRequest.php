<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Contacts\Get;

use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Endpoints\AbstractExpandableGetRequest;
use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Models\Contact;

/**
 * @extends AbstractExpandableGetRequest<ContactsGetResponse>
 */
class ContactsGetRequest extends AbstractExpandableGetRequest
{
    public function select(array $select): self
    {
        InvalidFormatException::checkAllowedValues($select, Contact::SELECT_FIELDS);
        return parent::select($select);
    }

    public function expandBy(array $expand): self
    {
        InvalidFormatException::checkAllowedValues($expand, Contact::EXPAND_FIELDS);
        return parent::expandBy($expand);
    }

    public function expandCustomFields(): self
    {
        return $this->expandBy(['customfields']);
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
