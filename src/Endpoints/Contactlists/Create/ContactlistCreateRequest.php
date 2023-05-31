<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Contactlists\Create;

use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Api;
use SmartEmailing\v3\Endpoints\AbstractRequest;
use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Models\Contactlist;

/**
 * @extends AbstractRequest<ContactlistCreateResponse>
 */
class ContactlistCreateRequest extends AbstractRequest
{
    protected ?Contactlist $contactList;

    public function __construct(Api $api, Contactlist $contactList = null)
    {
        parent::__construct($api);
        $this->contactList = $contactList;
    }

    /**
     * @return $this
     */
    public function setContactList(Contactlist $contactList): self
    {
        $this->contactList = $contactList;
        return $this;
    }

    public function contactList(): ?Contactlist
    {
        return $this->contactList;
    }

    public function toArray(): array
    {
        return $this->contactList !== null ? $this->contactList->jsonSerialize() : [];
    }

    protected function endpoint(): string
    {
        return 'contactlists';
    }

    protected function options(): array
    {
        PropertyRequiredException::throwIf('contactList', is_object($this->contactList));
        return parent::options();
    }

    protected function method(): string
    {
        return 'POST';
    }

    protected function createResponse(?ResponseInterface $response): ContactlistCreateResponse
    {
        return new ContactlistCreateResponse($response);
    }
}
