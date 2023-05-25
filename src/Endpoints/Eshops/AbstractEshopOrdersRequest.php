<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Eshops;

use SmartEmailing\v3\Endpoints\AbstractRequest;
use SmartEmailing\v3\Endpoints\AbstractResponse;
use SmartEmailing\v3\Models\OrderWithFeedItems;

/**
 * @template TResponse of AbstractResponse
 * @extends AbstractRequest<TResponse>
 * @deprecated Use import-orders instead
 */
abstract class AbstractEshopOrdersRequest extends AbstractRequest
{
    /**
     * @var OrderWithFeedItems[]
     */
    protected array $orders = [];

    /**
     * Creates Returns the newly created order
     */
    public function newOrder(?string $eshopName, ?string $eshopCode, ?string $emailAddress): OrderWithFeedItems
    {
        $order = new OrderWithFeedItems($eshopName, $eshopCode, $emailAddress);
        $this->addOrder($order);
        return $order;
    }

    public function addOrder(OrderWithFeedItems $order): self
    {
        $this->orders[] = $order;
        return $this;
    }

    /**
     * @return OrderWithFeedItems[]
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
