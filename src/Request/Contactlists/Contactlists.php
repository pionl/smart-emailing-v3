<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Contactlists;

use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Request\AbstractRequest;

class Contactlists extends AbstractRequest implements \JsonSerializable
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

    /** @var string[] */
    private $select = self::ALL_FIELDS;

    public function select(array $select): self
    {
        InvalidFormatException::checkAllowedValues($select, self::ALL_FIELDS);

        $this->select = $select;
        return $this;
    }

    protected function endpoint(): string
    {
        return 'contactlists';
    }

    protected function options(): array
    {
        return [
            'json' => $this->jsonSerialize()
        ];
    }

    private function toArray(): array
    {
        return [
            'select' => implode(',', $this->select),
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

}
