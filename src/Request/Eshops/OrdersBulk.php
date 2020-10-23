<?php

namespace SmartEmailing\v3\Request\Eshops;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Request\AbstractRequest;
use SmartEmailing\v3\Request\Eshops\Model\Order as OrderModel;
use SmartEmailing\v3\Request\Response;

class OrdersBulk extends AbstractRequest implements \JsonSerializable
{
	/**
	 * The maximum orders per single request
	 * @var int
	 */
	public $chunkLimit = 500;
	/**
	 * @var OrderModel[]
	 */
	protected $orders = [];

	public function __construct(Api $api)
	{
		parent::__construct($api);
	}

	/**
	 * @param OrderModel $order
	 *
	 * @return OrdersBulk
	 */
	public function addOrder(OrderModel $order): self
	{
		$this->orders[] = $order;
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
	 * @return array
	 */
	public function order(): array
	{
		return $this->orders;
	}

	/**
	 * Will send multiple requests because of the 500 count limit
	 * @inheritDoc
	 */
	public function send(): ?Response
	{
		// There is not enough contacts to enable chunk mode
		if ($this->chunkLimit >= count($this->orders)) {
			return parent::send();
		}

		return $this->sendInChunkMode();
	}

	/**
	 * Sends contact list in chunk mode
	 * @return Response
	 */
	protected function sendInChunkMode(): Response
	{
		// Store the original contact list
		$originalFullContactList = $this->orders;
		$lastResponse = null;

		// Chunk the array of contacts send it in multiple requests
		foreach (array_chunk($this->orders, $this->chunkLimit) as $orders) {
			// Store the contacts that will be passed
			$this->orders = $orders;

			$lastResponse = parent::send();
		}

		// Restore to original array
		$this->orders = $originalFullContactList;

		return $lastResponse;
	}

	//region AbstractRequest implementation
	protected function endpoint(): string
	{
		return 'orders-bulk';
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
		return $this->orders;
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
