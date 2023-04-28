<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Request\Eshops;

use SmartEmailing\v3\Request\Eshops\Model\Order;

class EshopOrders extends AbstractEshopOrders implements \JsonSerializable
{
    public function addOrder(Order $order): self
    {
        $this->orders = [];
        parent::addOrder($order);
        return $this;
    }

    public function order(): ?Order
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
