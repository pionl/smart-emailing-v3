<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Request\Contactlists;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Request\AbstractRequest;

class Contactlist extends AbstractRequest implements \JsonSerializable
{
    private const ALL_FIELDS = [
        'id',
        'name',
        'category',
        'publicname',
        'sendername',
        'senderemail',
        'replyto',
        'signature',
        'segment_id',
    ];

    /**
     * @var string[]
     */
    private array $select = self::ALL_FIELDS;

    private int $listId;

    public function __construct(Api $api, int $listId)
    {
        parent::__construct($api);

        $this->listId = $listId;
    }

    public function select(array $select): self
    {
        InvalidFormatException::checkAllowedValues($select, self::ALL_FIELDS);

        $this->select = $select;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    protected function endpoint(): string
    {
        return 'contactlists/' . $this->listId;
    }

    protected function options(): array
    {
        return [
            'json' => $this->jsonSerialize(),
        ];
    }

    private function toArray(): array
    {
        return [
            'select' => implode(',', $this->select),
        ];
    }
}
