<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

use SmartEmailing\v3\Exceptions\InvalidFormatException;

/**
 * Contact list wrapper with public properties (allows force set and easy getter). The fluent setter will help to set
 * values in correct format.
 */
class ContactListStatus extends Model
{
    public const CONFIRMED = 'confirmed';
    public const REMOVED = 'removed';
    public const UNSUBSCRIBED = 'unsubscribed';

    /**
     * @var int|null
     */
    public $id;

    /**
     * Contact's status in Contactlist. Allowed values: "confirmed", "unsubscribed", "removed"
     *
     * @var string Default value: confirmed
     */
    public $status = self::CONFIRMED;

    /**
     * ContactList constructor.
     *
     * @param int    $id
     * @param string $status Default value: confirmed
     */
    public function __construct($id, $status = null)
    {
        $this->setId($id);

        if ($status !== null) {
            $this->setStatus($status);
        }
    }

    /**
     * @param int $id
     *
     * @return ContactListStatus
     */
    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }

    /**
     * Contact's status in Contact-list. Allowed values: "confirmed", "unsubscribed", "removed"
     *
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        InvalidFormatException::checkInArray($status, [self::CONFIRMED, self::UNSUBSCRIBED, self::REMOVED]);
        $this->status = $status;
        return $this;
    }

    /**
     * Converts data to array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->removeEmptyValues($this->toArray());
    }
}
