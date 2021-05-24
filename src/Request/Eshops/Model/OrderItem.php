<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Eshops\Model;

use SmartEmailing\v3\Models\Model;
use SmartEmailing\v3\Request\Eshops\Model\Holder\Attributes;

/**
 * OrderItem wrapper with public properties (allows force set and easy getter). The fluent setter will help
 * to set values in correct format.
 * @package SmartEmailing\v3\Request\Eshops\Model
 */
class OrderItem extends Model
{
	/** @var string|null required */
	public $id;
	/** @var string|null required */
	public $name;
	/** @var string|null */
	public $description;
	/** @var Price required */
	public $price;
	/** @var int required */
	public $quantity = 0;
	/** @var string required */
	public $url = '';
	/** @var string|null */
	public $image_url;
	/** @var Attributes */
	protected $attributes;

	/**
	 * OrderItem constructor.
	 * @param string $id
	 * @param string $name
	 * @param int $quantity
	 * @param Price $price
	 * @param string $url
	 */
	public function __construct($id, $name, $quantity, Price $price, $url)
	{
		$this->setId($id);
		$this->setName($name);
		$this->setQuantity($quantity);
		$this->setPrice($price);
		$this->setUrl($url);
		$this->attributes = new Attributes();
	}

	/**
	 * @param $id
	 * @return OrderItem
	 */
	public function setId(string $id): OrderItem
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @param string|null $name
	 * @return OrderItem
	 */
	public function setName(string $name): OrderItem
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @param string|null $description
	 * @return OrderItem
	 */
	public function setDescription(string $description): OrderItem
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @param Price|null $price
	 * @return OrderItem
	 */
	public function setPrice(Price $price): OrderItem
	{
		$this->price = $price;
		return $this;
	}

	/**
	 * @param int $quantity
	 * @return OrderItem
	 */
	public function setQuantity(int $quantity): OrderItem
	{
		$this->quantity = $quantity;
		return $this;
	}

	/**
	 * @param string $url
	 * @return OrderItem
	 */
	public function setUrl(string $url): OrderItem
	{
		$this->url = $url;
		return $this;
	}

	/**
	 * @param string|null $image_url
	 * @return OrderItem
	 */
	public function setImageUrl(?string $image_url): OrderItem
	{
		$this->image_url = $image_url;
		return $this;
	}

	/**
	 * @return Attributes
	 */
	public function attributes(): Attributes
	{
		return $this->attributes;
	}

	/**
	 * Converts data to array
	 * @return array
	 */
	public function toArray(): array
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'description' => $this->description,
			'price' => $this->price,
			'quantity' => $this->quantity,
			'url' => $this->url,
			'image_url' => $this->image_url,
			'attributes' => $this->attributes,
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
