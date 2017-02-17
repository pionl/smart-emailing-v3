<?php
namespace SmartEmailing\v3\Request\Import;

use SmartEmailing\v3\Models\Model;

/**
 * Contact field wrapper with public properties (allows force set and easy getter). The fluent setter will help
 * to set values in correct format.
 * @package SmartEmailing\v3\Request\Import
 */
class CustomField extends Model
{
    /**
     * @var int|null
     */
    public $id = null;

    /**
     * Array of Customfields options IDs matching with selected Custom-field. Required for composite custom-fields
     * @var array
     */
    public $options = [];

    /**
     * String value for simple custom-fields, and YYYY-MM-DD HH:MM:SS for date custom-fields. Value size is limited to
     * 64KB. Required for simple custom-fields
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
     * Adds a CustomField id for composite custom-fields
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
     * String value for simple custom-fields, and YYYY-MM-DD HH:MM:SS for date custom-fields. Value size is limited to
     * 64KB. Required for simple custom-fields
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
        return [
            'id' => $this->id,
            'options' => $this->options,
            'value' => $this->value
        ];
    }
}