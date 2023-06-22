<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models\Holder;

use SmartEmailing\v3\Models\AbstractMapHolder;
use SmartEmailing\v3\Models\Model;
use SmartEmailing\v3\Models\OrderItem;
use SmartEmailing\v3\Models\Price;

/**
 * @extends AbstractMapHolder<OrderItem>
 */
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
     */
    public function create(int $id, string $name, int $quantity, Price $price, string $url): OrderItem
    {
        $list = new OrderItem($id, $name, $quantity, $price, $url);
        $this->add($list);
        return $list;
    }

    protected function entryKey(Model $entry): ?string
    {
        return $entry->getId();
    }
}
