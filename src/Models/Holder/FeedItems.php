<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models\Holder;

use SmartEmailing\v3\Models\AbstractMapHolder;
use SmartEmailing\v3\Models\FeedItem;
use SmartEmailing\v3\Models\Model;

/**
 * @extends AbstractMapHolder<FeedItem>
 */
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
    public function create(string $idItem, string $feedName, int $quantity): FeedItem
    {
        $list = new FeedItem($idItem, $feedName, $quantity);
        $this->add($list);
        return $list;
    }

    protected function entryKey(Model $entry): ?string
    {
        return $entry->getId();
    }
}
