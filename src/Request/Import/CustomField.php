<?php
namespace SmartEmailing\v3\Request\Import;

/**
 * Contact field wrapper with public properties (allows force set and easy getter). The fluent setter will help
 * to set values in correct format.
 * @package SmartEmailing\v3\Request\Import
 */
class CustomField implements \JsonSerializable
{
    /**
     * @var int|null
     */
    public $id = null;

    /**
     * Array of Customfields options IDs matching with selected Customfield. Required for composite customfields
     * @var array
     */
    public $options = [];

    /**
     * String value for simple customfields, and YYYY-MM-DD HH:MM:SS for date customfields. Value size is limited to
     * 64KB. Required for simple customfields
     * @var string|null
     */
    public $value = null;

    /**
     * CustomField constructor.
     *
     * @param int|null    $id
     * @param string|null $value
     */
    public function __construct($id, $value = null)
    {
        $this->setId($id);

        if (!is_null($value)) {
            $this->setValue($value);
        }
    }


    /**
     * @param int|null $id
     *
     * @return CustomField
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Array of Customfields options IDs matching with selected Customfield. Required for composite customfields
     *
     * @param array $options
     *
     * @return CustomField
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Adds a CustomField id for composite customfields
     *
     * @param int $customFiledId
     *
     * @return $this
     */
    public function addOption($customFiledId)
    {
        $this->options[] = intval($customFiledId);
        return $this;
    }

    /**
     * String value for simple customfields, and YYYY-MM-DD HH:MM:SS for date customfields. Value size is limited to
     * 64KB. Required for simple customfields
     *
     * @param null|string $value
     *
     * @return CustomField
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }


    /**
     * Converts data to array
     * @return array
     */
    public function toArray()
    {
        $array = [
            'id' => $this->id
        ];

        if (!empty($this->options)) {
            $array['options'] = $this->options;
        }
        if (!is_null($this->value)) {
            $array['value'] = $this->value;
        }
        return $array;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}