<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Eshops\Model\Holder;

use SmartEmailing\v3\Models\AbstractMapHolder;
use SmartEmailing\v3\Request\Eshops\Model\OrderItem;
use SmartEmailing\v3\Request\Eshops\Model\Price;

/**
 * Class OrderItems
 *
 * @package SmartEmailing\v3\Request\Eshops\Model\Holder
 */
class OrderItems extends AbstractMapHolder
{
	/**
	 * Inserts order item into the items. Unique items only.
	 *
	 * @param OrderItem $list
	 *
	 * @return $this
	 */
	public function add(OrderItem $list): OrderItems
	{
		$this->insertEntry($list);
		return $this;
	}

	/**
	 * Creates OrderItem entry and inserts it to the array
	 *
	 * @param int $id
	 * @param string $name
	 * @param int $quantity
	 * @param Price $price
	 * @param string $url
	 *
	 * @return OrderItem
	 */
	public function create($id, $name, $quantity, Price $price, $url): OrderItem
	{
		$list = new OrderItem($id, $name, $quantity, $price, $url);
		$this->add($list);
		return $list;
	}
}
