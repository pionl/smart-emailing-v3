<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Contacts\Search;

use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Endpoints\AbstractSearchRequest;
use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Models\Contact;

/**
 * @extends AbstractSearchRequest<ContactsSearchResponse, ContactsSearchFilters>
 */
class ContactsSearchRequest extends AbstractSearchRequest
{
    public function expandBy(array $expand): self
    {
        InvalidFormatException::checkAllowedValues($expand, Contact::EXPAND_FIELDS);
        return parent::expandBy($expand);
    }

    public function select(array $select): self
    {
        InvalidFormatException::checkAllowedValues($select, Contact::SELECT_FIELDS);
        return parent::select($select);
    }

    /**
     * @param string|string[] $sort
     */
    public function sortBy($sort): self
    {
        InvalidFormatException::checkAllowedSortValues($sort, Contact::SORT_FIELDS);
        return parent::sortBy($sort);
    }

    protected function createFilters(): ContactsSearchFilters
    {
        return new ContactsSearchFilters();
    }

    protected function endpoint(): string
    {
        return 'contacts';
    }

    protected function createResponse(?ResponseInterface $response): ContactsSearchResponse
    {
        return new ContactsSearchResponse($response);
    }
}
