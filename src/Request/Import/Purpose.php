<?php
namespace SmartEmailing\v3\Request\Import;

use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Models\Model;

/**
 * Processing purposes assigned to contact. Every purpose may be assigned multiple times for different
 * time intervals. Exact duplicities will be silently skipped.
 * @package SmartEmailing\v3\Request\Import
 */
class Purpose extends Model
{
    /**
     * @var int|null
     */
    public $id;
    /**
     * Date and time since processing purpose is valid in YYYY-MM-DD HH:MM:SS format. If empty, current date
     * and time will be used.
     * @var string Default value: null
     */
    public $valid_from = null;

    /**
     * Date and time of processing purpose validity end in YYYY-MM-DD HH:MM:SS format. If empty, it will be
     * calculated as valid_from + default duration of particular purpose.
     * @var string Default value: null
     */
    public $valid_to = null;

    /**
     * Purpose constructor.
     *
     * @param int           $id
     * @param string|null   $valid_from Default value: null
     * @param string|null   $valid_to Default value: null
     */
    public function __construct($id, $valid_from = null, $valid_to = null)
    {
        $this->setId($id);

        if (!is_null($valid_from)) {
            $this->setValidFrom($valid_from);
        }

        if (!is_null($valid_to)) {
            $this->setValidTo($valid_to);
        }
    }

    /**
     * @param int $id
     *
     * @return Purpose
     */
    public function setId($id)
    {
        $this->id = intval($id);
        return $this;
    }

    /**
     * Date and time since processing purpose is valid. Allowed format: YYYY-MM-DD HH:MM:SS.
     *
     * @param string $valid_from
     *
     * @return Purpose
     */
    public function setValidFrom($valid_from)
    {
        if(!preg_match('(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})', $valid_from))
            throw new InvalidFormatException(sprintf('Invalid date and time format. Format must be YYYY-MM-DD HH:MM:SS, %s given.', $valid_from));

        $this->valid_from = $valid_from;
        return $this;
    }

    /**
     * Date and time of processing purpose validity end. Allowed format: YYYY-MM-DD HH:MM:SS.
     *
     * @param string $valid_to
     *
     * @return Purpose
     */
    public function setValidTo($valid_to)
    {
        if(!preg_match('(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})', $valid_to))
            throw new InvalidFormatException(sprintf('Invalid date and time format. Format must be YYYY-MM-DD HH:MM:SS, %s given.', $valid_to));

        $this->valid_to = $valid_to;
        return $this;
    }


    /**
     * Converts data to array
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'valid_from' => $this->valid_from,
            'valid_to' => $this->valid_to
        ];
    }

    public function jsonSerialize(): array
    {
        // Don't remove null/empty values - not needed
        return $this->toArray();
    }
}
