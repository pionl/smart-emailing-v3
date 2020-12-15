<?php


namespace SmartEmailing\v3\Request\Eshops;

use JsonSerializable;
use SmartEmailing\v3\Request\AbstractRequest;
use SmartEmailing\v3\Request\Eshops\Model\Order;

/**
 * Class AbstractEshopOrders
 * @package SmartEmailing\v3\Request\Eshops
 */
abstract class AbstractEshopOrders extends AbstractRequest implements JsonSerializable
{
	/**
	 * @var Order[]
	 */
	protected array $orders = [];

	/**
	 * Creates Returns the newly created order
	 *
	 * @param $eshopName
	 * @param $eshopCode
	 * @param $emailAddress
	 *
	 * @return Order
	 */
	public function newOrder($eshopName, $eshopCode, $emailAddress): Order
	{
		$order = new Order($eshopName, $eshopCode, $emailAddress);
		$this->addOrder($order);
		return $order;
	}

	/**
	 * @param Order $order
	 *
	 * @return AbstractEshopOrders
	 */
	public function addOrder(Order $order): AbstractEshopOrders
	{
		$this->orders[] = $order;
		return $this;
	}

	/**
	 * @return Order[]
	 */
	public function orders(): array
	{
		return $this->orders;
	}

	/**
	 * @return array[]
	 */
	protected function options(): array
	{
		return [
			'json' => $this->jsonSerialize()
		];
	}

	/**
	 * @return string
	 */
	protected function method(): string
	{
		return 'POST';
	}

	/**
	 * @return array
	 */
	public function jsonSerialize(): array
	{
		return $this->toArray();
	}
	/**
	 * Converts data to array
	 * @return array
	 */
	public function toArray(): array
	{
		return $this->orders;
	}

}
