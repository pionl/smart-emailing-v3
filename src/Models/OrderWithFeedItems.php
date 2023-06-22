<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

use SmartEmailing\v3\Models\Holder\Attributes;
use SmartEmailing\v3\Models\Holder\FeedItems;
use SmartEmailing\v3\Models\Holder\OrderItems;

/**
 * Order wrapper with public properties (allows force set and easy getter). The fluent setter will help to set values in
 * correct format.
 *
 * @deprecated
 */
class OrderWithFeedItems extends Order
{
    private FeedItems $feedItems;

    public function __construct(?string $eshopName, ?string $eshopCode, ?string $emailAddress)
    {
        parent::__construct($eshopName, $eshopCode, $emailAddress);
        $this->feedItems = new FeedItems();
    }

    public function feedItems(): FeedItems
    {
        return $this->feedItems;
    }

    /**
     * @return array{emailaddress: string|null, eshop_name: string|null, eshop_code: string|null, status: string, paid_at: string|null, created_at: string|null, attributes: Attributes, items: OrderItems, item_feeds: FeedItems}
     */
    public function toArray(): array
    {
        $array = parent::toArray();
        $array['item_feeds'] = $this->feedItems;
        return $array;
    }
}
