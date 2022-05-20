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
	/** @var string required */
	public $id;
	/** @var string required */
	public $feedName;
	/** @var int required */
	public $quantity = 0;

	/**
	 * ItemFeed constructor.
	 * @param $id
	 * @param $feedName
	 * @param $quantity
	 */
	public function __construct($id, $feedName, $quantity)
	{
		$this->id = $id;
		$this->feedName = $feedName;
		$this->quantity = $quantity;
	}

	/**
	 * @param string $id
	 * @return FeedItem
	 */
	public function setId(string $id): FeedItem
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @param string $feedName
	 * @return FeedItem
	 */
	public function setFeedName(string $feedName): FeedItem
	{
		$this->feedName = $feedName;
		return $this;
	}

	/**
	 * @param int $quantity
	 * @return FeedItem
	 */
	public function setQuantity(int $quantity): FeedItem
	{
		$this->quantity = $quantity;
		return $this;
	}

	/**
	 * Converts data to array
	 * @return array
	 */
	public function toArray(): array
	{
		return [
			'item_id' => $this->id,
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
