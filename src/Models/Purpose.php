<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

use SmartEmailing\v3\Exceptions\InvalidFormatException;

/**
 * Processing purposes assigned to contact. Every purpose may be assigned multiple times for different time intervals.
 * Exact duplicities will be silently skipped.
 */
class Purpose extends Model
{
    public ?int $id = null;

    /**
     * Date and time since processing purpose is valid in YYYY-MM-DD HH:MM:SS format. If empty, current date and time
     * will be used.
     */
    public ?string $valid_from = null;

    /**
     * Date and time of processing purpose validity end in YYYY-MM-DD HH:MM:SS format. If empty, it will be calculated
     * as valid_from + default duration of particular purpose.
     */
    public ?string $valid_to = null;

    /**
     * @param int|numeric-string  $id
     */
    public function __construct($id, ?string $valid_from = null, ?string $valid_to = null)
    {
        $this->setId($id);

        if ($valid_from !== null) {
            $this->setValidFrom($valid_from);
        }

        if ($valid_to !== null) {
            $this->setValidTo($valid_to);
        }
    }

    /**
     * @param int|numeric-string $id
     */
    public function setId($id): self
    {
        $this->id = (int) $id;
        return $this;
    }

    /**
     * Date and time since processing purpose is valid. Allowed format: YYYY-MM-DD HH:MM:SS.
     */
    public function setValidFrom(string $valid_from): self
    {
        if (preg_match('(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})', $valid_from) !== 1) {
            throw new InvalidFormatException(sprintf(
                'Invalid date and time format. Format must be YYYY-MM-DD HH:MM:SS, %s given.',
                $valid_from
            ));
        }

        $this->valid_from = $valid_from;
        return $this;
    }

    /**
     * Date and time of processing purpose validity end. Allowed format: YYYY-MM-DD HH:MM:SS.
     */
    public function setValidTo(string $valid_to): self
    {
        if (preg_match('(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})', $valid_to) !== 1) {
            throw new InvalidFormatException(sprintf(
                'Invalid date and time format. Format must be YYYY-MM-DD HH:MM:SS, %s given.',
                $valid_to
            ));
        }

        $this->valid_to = $valid_to;
        return $this;
    }

    /**
     * @return array{id: int|null, valid_from: string|null, valid_to: string|null}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'valid_from' => $this->valid_from,
            'valid_to' => $this->valid_to,
        ];
    }
}
