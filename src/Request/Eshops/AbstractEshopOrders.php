<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Request\Eshops;

use SmartEmailing\v3\Request\AbstractRequest;
use SmartEmailing\v3\Request\Eshops\Model\Order;
use SmartEmailing\v3\Request\Response;

/**
 * @template TResponse of Response
 * @extends AbstractRequest<TResponse>
 */
abstract class AbstractEshopOrders extends AbstractRequest implements \JsonSerializable
{
    /**
     * @var Order[]
     */
    protected array $orders = [];

    /**
     * Creates Returns the newly created order
     */
    public function newOrder($eshopName, $eshopCode, $emailAddress): Order
    {
        $order = new Order($eshopName, $eshopCode, $emailAddress);
        $this->addOrder($order);
        return $order;
    }

    public function addOrder(Order $order): self
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

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Converts data to array
     */
    public function toArray(): array
    {
        return $this->orders;
    }

    /**
     * @return array[]
     */
    protected function options(): array
    {
        return [
            'json' => $this->jsonSerialize(),
        ];
    }

    protected function method(): string
    {
        return 'POST';
    }
}
