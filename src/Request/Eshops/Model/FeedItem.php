<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Request\Eshops\Model;

use SmartEmailing\v3\Models\Model;

/**
 * ItemFeed wrapper with public properties (allows force set and easy getter). The fluent setter will help to set values
 * in correct format.
 */
class FeedItem extends Model
{
    /**
     * @var string required
     */
    public $id;

    /**
     * @var string required
     */
    public $feedName;

    /**
     * @var int required
     */
    public $quantity = 0;

    public function __construct($id, $feedName, $quantity)
    {
        $this->id = $id;
        $this->feedName = $feedName;
        $this->quantity = $quantity;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setFeedName(string $feedName): self
    {
        $this->feedName = $feedName;
        return $this;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Converts data to array
     */
    public function toArray(): array
    {
        return [
            'item_id' => $this->id,
            'feed_name' => $this->feedName,
            'quantity' => $this->quantity,
        ];
    }

    public function jsonSerialize(): array
    {
        // Don't remove null/empty values - not needed
        return $this->toArray();
    }
}
