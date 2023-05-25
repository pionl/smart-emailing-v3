<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Import\Orders;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Endpoints\Import\AbstractImportRequest;
use SmartEmailing\v3\Models\ImportOrdersSettings;
use SmartEmailing\v3\Models\Order;

class ImportOrdersRequest extends AbstractImportRequest
{
    protected ImportOrdersSettings $settings;

    public function __construct(Api $api)
    {
        parent::__construct($api);
        $this->settings = new ImportOrdersSettings();
    }

    /**
     * @return $this
     */
    public function addOrder(Order $order)
    {
        $this->data[] = $order;
        return $this;
    }

    /**
     * Creates new order and adds to the order list. Returns the newly created order
     */
    public function newOrder(?string $eshopName, ?string $eshopCode, ?string $emailAddress): Order
    {
        $order = new Order($eshopName, $eshopCode, $emailAddress);
        $this->addOrder($order);
        return $order;
    }

    /**
     * @return Order[]
     */
    public function orders(): array
    {
        return $this->data;
    }

    public function settings(): ImportOrdersSettings
    {
        return $this->settings;
    }

    protected function endpoint(): string
    {
        return 'import-orders';
    }
}
