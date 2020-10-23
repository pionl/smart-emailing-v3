<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Eshops\Model;

use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Models\Model;

/**
 * ItemFeed wrapper with public properties (allows force set and easy getter). The fluent setter will help
 * to set values in correct format.
 * @package SmartEmailing\v3\Request\Eshops\Model
 */
class FeedItem extends Model
{
	/** @var int required */
	public $idItem;
	/** @var string required */
	public $feedName;
	/** @var int required */
	public $quantity = 0;

	/**
	 * ItemFeed constructor.
	 * @param $idItem
	 * @param $feedName
	 * @param $quantity
	 */
	public function __construct($idItem, $feedName, $quantity)
	{
		$this->idItem = $idItem;
		$this->feedName = $feedName;
		$this->quantity = $quantity;
	}

	/**
	 * Converts data to array
	 * @return array
	 */
	public function toArray(): array
	{
		return [
			'item_id' => $this->idItem,
			'feed_name' => $this->feedName,
			'quantity' => $this->quantity,
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
