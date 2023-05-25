<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Contactlists\Get;

use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Endpoints\AbstractGetRequest;
use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Models\Contactlist;

/**
 * @extends AbstractGetRequest<ContactlistGetResponse>
 */
class ContactlistGetRequest extends AbstractGetRequest
{
    public function select(array $select): self
    {
        InvalidFormatException::checkAllowedValues($select, Contactlist::SELECT_FIELDS);
        return parent::select($select);
    }

    protected function endpoint(): string
    {
        return 'contactlists/' . $this->getItemId();
    }

    protected function createResponse(?ResponseInterface $response): ContactlistGetResponse
    {
        return new ContactlistGetResponse($response);
    }
}
