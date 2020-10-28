<?php

namespace SmartEmailing\v3\Request\Eshops;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Request\AbstractRequest;
use SmartEmailing\v3\Request\Eshops\Model\Order;
use SmartEmailing\v3\Request\Response;

/**
 * Class OrdersBulk
 * Method is used to import orders in bulk. Up to 500 orders per request is allowed.
 * This creates new activities which can be used for scoring, segmentation and automation.
 *
 * @package SmartEmailing\v3\Request\Eshops
 */
class EshopOrdersBulk extends AbstractRequest implements \JsonSerializable
{
	/**
	 * The maximum orders per single request
	 * @var int
	 */
	public $chunkLimit = 500;
	/**
	 * @var Order[]
	 */
	protected $orders = [];

	public function __construct(Api $api)
	{
		parent::__construct($api);
	}

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
	 * @return EshopOrdersBulk
	 */
	public function addOrder(Order $order): EshopOrdersBulk
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
		$originalFullOrdersList = $this->orders;
		$lastResponse = null;

		// Chunk the array of contacts send it in multiple requests
		foreach (array_chunk($this->orders, $this->chunkLimit) as $orders) {
			// Store the contacts that will be passed
			$this->orders = $orders;

			$lastResponse = parent::send();
		}

		// Restore to original array
		$this->orders = $originalFullOrdersList;

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
