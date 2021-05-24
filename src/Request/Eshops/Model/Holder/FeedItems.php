<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Eshops\Model\Holder;

use SmartEmailing\v3\Models\AbstractMapHolder;
use SmartEmailing\v3\Request\Eshops\Model\FeedItem;

/**
 * Class FeedItems
 *
 * @package SmartEmailing\v3\Request\Eshops\Model\Holder
 */
class FeedItems extends AbstractMapHolder
{
	/**
	 * Inserts feed item into the feed items.
	 *
	 * @param FeedItem $list
	 *
	 * @return FeedItems
	 */
	public function add(FeedItem $list): FeedItems
	{
		$this->insertEntry($list);
		return $this;
	}

	/**
	 * Creates FeedItem entry and inserts it to the array
	 *
	 * @param $idItem
	 * @param $feedName
	 * @param $quantity
	 *
	 * @return FeedItem
	 */
	public function create($idItem, $feedName, $quantity): FeedItem
	{
		$list = new FeedItem($idItem, $feedName, $quantity);
		$this->add($list);
		return $list;
	}
}
