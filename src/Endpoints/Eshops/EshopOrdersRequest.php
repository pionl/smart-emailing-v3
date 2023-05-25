<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Eshops;

use SmartEmailing\v3\Models\OrderWithFeedItems;

/**
 * @deprecated Use import-orders instead
 */
class EshopOrdersRequest extends AbstractEshopOrdersRequest
{
    public function addOrder(OrderWithFeedItems $order): self
    {
        $this->orders = [];
        parent::addOrder($order);
        return $this;
    }

    public function order(): ?OrderWithFeedItems
    {
        if ($this->orders !== []) {
            return current($this->orders);
        }

        return null;
    }

    /**
     * Converts data to array
     */
    public function toArray(): array
    {
        if ($this->order() === null) {
            return [];
        }

        return $this->order()
            ->toArray();
    }

    protected function endpoint(): string
    {
        return 'orders';
    }
}
