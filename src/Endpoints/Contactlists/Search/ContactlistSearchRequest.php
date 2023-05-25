<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Contactlists\Search;

use SmartEmailing\v3\Endpoints\AbstractBasicSearchRequest;
use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Models\Contactlist;

/**
 * @extends AbstractBasicSearchRequest<ContactlistSearchResponse>
 */
class ContactlistSearchRequest extends AbstractBasicSearchRequest
{
    public function select(array $select): self
    {
        InvalidFormatException::checkAllowedValues($select, Contactlist::SELECT_FIELDS);
        return parent::select($select);
    }

    protected function endpoint(): string
    {
        return 'contactlists';
    }
}
