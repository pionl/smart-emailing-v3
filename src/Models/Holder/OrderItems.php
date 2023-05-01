<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models\Holder;

use SmartEmailing\v3\Models\AbstractMapHolder;
use SmartEmailing\v3\Models\OrderItem;
use SmartEmailing\v3\Models\Price;

class OrderItems extends AbstractMapHolder
{
    /**
     * Inserts order item into the items. Unique items only.
     *
     * @return $this
     */
    public function add(OrderItem $list): self
    {
        $this->insertEntry($list);
        return $this;
    }

    /**
     * Creates OrderItem entry and inserts it to the array
     *
     * @param int $id
     * @param string $name
     * @param int $quantity
     * @param string $url
     */
    public function create($id, $name, $quantity, Price $price, $url): OrderItem
    {
        $list = new OrderItem($id, $name, $quantity, $price, $url);
        $this->add($list);
        return $list;
    }
}
