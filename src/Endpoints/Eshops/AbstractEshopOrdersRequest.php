<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Eshops;

use SmartEmailing\v3\Endpoints\AbstractRequest;
use SmartEmailing\v3\Endpoints\AbstractResponse;
use SmartEmailing\v3\Models\Order;

/**
 * @template TResponse of AbstractResponse
 * @extends AbstractRequest<TResponse>
 * @deprecated Use import-orders instead
 */
abstract class AbstractEshopOrdersRequest extends AbstractRequest
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

    public function toArray(): array
    {
        return $this->orders;
    }

    protected function method(): string
    {
        return 'POST';
    }
}
