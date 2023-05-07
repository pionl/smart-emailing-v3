<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

/**
 * ItemFeed wrapper with public properties (allows force set and easy getter). The fluent setter will help to set values
 * in correct format.
 */
class FeedItem extends Model
{
    /**
     * @var string required
     */
    public string $id;

    /**
     * @var string required
     */
    public string $feedName;

    /**
     * @var int required
     */
    public int $quantity = 0;

    public function __construct(string $id, string $feedName, int $quantity)
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
     * @return array{item_id: string, feed_name: string, quantity: int}
     */
    public function toArray(): array
    {
        return [
            'item_id' => $this->id,
            'feed_name' => $this->feedName,
            'quantity' => $this->quantity,
        ];
    }
}
