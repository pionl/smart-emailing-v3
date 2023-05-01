<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models\Holder;

use SmartEmailing\v3\Models\AbstractMapHolder;
use SmartEmailing\v3\Models\FeedItem;

class FeedItems extends AbstractMapHolder
{
    /**
     * Inserts feed item into the feed items.
     */
    public function add(FeedItem $list): self
    {
        $this->insertEntry($list);
        return $this;
    }

    /**
     * Creates FeedItem entry and inserts it to the array
     */
    public function create($idItem, $feedName, $quantity): FeedItem
    {
        $list = new FeedItem($idItem, $feedName, $quantity);
        $this->add($list);
        return $list;
    }
}
