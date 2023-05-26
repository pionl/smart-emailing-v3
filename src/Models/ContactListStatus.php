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

    public ?int $id = null;

    /**
     * Contact's status in Contactlist. Allowed values: "confirmed", "unsubscribed", "removed"
     */
    public string $status = self::CONFIRMED;

    public function __construct(int $id = null, ?string $status = self::CONFIRMED)
    {
        if ($id !== null) {
            $this->setId($id);
        }

        if ($status !== null) {
            $this->setStatus($status);
        }
    }

    public function setId(int $id): self
    {
        $this->id = (int) $id;
        return $this;
    }

    /**
     * Contact's status in Contact-list. Allowed values: "confirmed", "unsubscribed", "removed"
     *
     * @return $this
     */
    public function setStatus(string $status)
    {
        InvalidFormatException::checkInArray($status, [self::CONFIRMED, self::UNSUBSCRIBED, self::REMOVED]);
        $this->status = $status;
        return $this;
    }

    /**
     * @return array{id: int|null, status: string}
     */
    public function toArray(): array
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

    /**
     * @return static
     */
    public static function fromJSON(\stdClass $json): object
    {
        $item = parent::fromJSON($json);
        if (isset($json->contactlist_id)) {
            $item->id = (int) $json->contactlist_id;
        }
        return $item;
    }
}
