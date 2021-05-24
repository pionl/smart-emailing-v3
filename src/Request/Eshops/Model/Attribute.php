<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Eshops\Model;

use SmartEmailing\v3\Models\Model;

/**
 * Attribute field wrapper with public properties (allows force set and easy getter). The fluent setter will help
 * to set values in correct format.
 * @package SmartEmailing\v3\Request\Eshops\Model
 */
class Attribute extends Model
{
	/**
	 * @var string|null
	 */
	public $name = null;

	/**
	 * String value for simple custom-fields, and YYYY-MM-DD HH:MM:SS for date custom-fields. Value size is limited to
	 * 64KB. Required for simple custom-fields
	 * @var string|null
	 */
	public $value = null;

	/**
	 * Attribute constructor.
	 * @param $name
	 * @param $value
	 */
	public function __construct($name, $value)
	{
		$this->name = $name;
		$this->value = $value;
	}

	/**
	 * @param string|null $name
	 * @return Attribute
	 */
	public function setName(string $name): Attribute
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @param string|null $value
	 * @return Attribute
	 */
	public function setValue(string $value): Attribute
	{
		$this->value = $value;
		return $this;
	}

	/**
	 * Converts data to array
	 * @return array
	 */
	public function toArray(): array
	{
		return [
			'name' => $this->name,
			'value' => $this->value
		];
	}

	/**
	 * @return array
	 */
	public function jsonSerialize(): array
	{
		// Don't remove null/empty values - not needed
		return $this->toArray();
	}
}
