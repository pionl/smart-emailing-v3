<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Eshops;

use SmartEmailing\v3\Request\Eshops\Model\Order;

/**
 * Class Orders
 * @package SmartEmailing\v3\Request\Eshops
 */
class EshopOrders extends AbstractEshopOrders implements \JsonSerializable
{
	/**
	 * @param  $order
	 *
	 * @return EshopOrders
	 */
	public function addOrder(Order $order): EshopOrders
	{
		$this->orders = [];
		parent::addOrder($order);
		return $this;
	}

	/**
	 * @return Order|null
	 */
	public function order(): ?Order
	{
		if (count($this->orders)) {
			return current($this->orders);
		}
		return null;
	}

	/**
	 * @return string
	 */
	protected function endpoint(): string
	{
		return 'orders';
	}

	/**
	 * Converts data to array
	 * @return array
	 */
	public function toArray(): array
	{
		if (is_null($this->order())) {
			return [];
		}
		return $this->order()->toArray();
	}
}
