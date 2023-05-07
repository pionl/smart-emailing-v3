<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Contactlists\Get;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Endpoints\AbstractRequest;
use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Models\Contactlist;

/**
 * @extends AbstractRequest<ContactlistGetResponse>
 */
class ContactlistGetRequest extends AbstractRequest
{
    /**
     * @var string[]
     */
    private array $select = Contactlist::SELECT_FIELDS;

    private int $listId;

    public function __construct(Api $api, int $listId)
    {
        parent::__construct($api);

        $this->listId = $listId;
    }

    public function select(array $select): self
    {
        InvalidFormatException::checkAllowedValues($select, Contactlist::SELECT_FIELDS);
        $this->select = $select;
        return $this;
    }

    /**
     * @return array{select: string}
     */
    public function toArray(): array
    {
        return [
            'select' => implode(',', $this->select),
        ];
    }

    protected function endpoint(): string
    {
        return 'contactlists/' . $this->listId;
    }
}
