<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Eshops;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Request\AbstractRequest;
use SmartEmailing\v3\Request\Eshops\Model\Order;

/**
 * Class Orders
 * @package SmartEmailing\v3\Request\Eshops
 */
class EshopOrders extends EshopOrdersBulk implements \JsonSerializable
{
	/**
	 * @param  $order
	 *
	 * @return EshopOrders
	 */
	public function addOrder(Order $order): EshopOrdersBulk
	{
		$this->orders = [];
		$this->orders[] = $order;
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

	//region AbstractRequest implementation
	protected function endpoint(): string
	{
		return 'orders';
	}

	protected function options(): array
	{
		return [
			'json' => $this->jsonSerialize()
		];
	}

	protected function method(): string
	{
		return 'POST';
	}

	//endregion

	//region Data convert
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
