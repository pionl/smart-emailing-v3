<?php
namespace SmartEmailing\v3\Request\CustomFields;

use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Models\Model;

class CustomField extends Model
{
    const TEXT = 'text';
    const TEXT_AREA = "textarea";
    const DATE = "date";
    const CHECKBOX = "checkbox";
    const RADIO = "radio";
    const SELECT = "select";

    public $id = null;
    public $name = null;
    public $type = null;
    public $options = [];

    /**
     * CustomField constructor.
     *
     * @param string|null $name
     * @param string|null $type
     */
    public function __construct($name = null, $type = null)
    {
        if (!is_null($name)) {
            $this->setName($name);
        }

        if (!is_null($type)) {
            $this->setType($type);
        }
    }

    /**
     * Returns a list of supported types
     * @return array
     */
    public static function types()
    {
        return [
            self::TEXT,
            self::TEXT_AREA,
            self::DATE,
            self::CHECKBOX,
            self::RADIO,
            self::SELECT
        ];
    }

    /**
     * @param null $id
     *
     * @return CustomField
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Adds an option id
     * @param int $id
     *
     * @return $this
     */
    public function addOption($id)
    {
        $this->options[] = $id;
        return $this;
    }

    /**
     * @param mixed $name
     *
     * @return CustomField
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $type
     *
     * @return CustomField
     * @throws InvalidFormatException
     */
    public function setType($type)
    {
        // Check if valid
        InvalidFormatException::checkInArray($type, static::types());
        $this->type = $type;
        return $this;
    }

    /**
     * Returns the array representation
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'options' => $this->options
        ];
    }
}