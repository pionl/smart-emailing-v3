<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Eshops;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Request\AbstractRequest;
use SmartEmailing\v3\Request\Eshops\Model\Order as OrderModel;

class Order extends AbstractRequest implements \JsonSerializable
{
	/**
	 * @var OrderModel
	 */
	protected $order;

	public function __construct(Api $api)
	{
		parent::__construct($api);
	}

	/**
	 * @param OrderModel $order
	 *
	 * @return Order
	 */
	public function addOrder(OrderModel $order): self
	{
		$this->order = $order;
		return $this;
	}

	/**
	 * Creates Returns the newly created contact
	 *
	 * @param $eshopName
	 * @param $eshopCode
	 * @param $emailAddress
	 *
	 * @return OrderModel
	 */
	public function newOrder($eshopName, $eshopCode, $emailAddress): OrderModel
	{
		$order = new OrderModel($eshopName, $eshopCode, $emailAddress);
		$this->addOrder($order);
		return $order;
	}

	/**
	 * @return OrderModel
	 */
	public function order(): OrderModel
	{
		return $this->order;
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
		return $this->order->toArray();
	}

	/**
	 * @return array
	 */
	public function jsonSerialize(): array
	{
		return $this->toArray();
	}
	//endregion

}
