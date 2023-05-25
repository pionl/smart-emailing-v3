<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

/**
 * Settings for import.
 */
class ImportOrdersSettings extends Model
{
    /**
     * Settings for skipping invalid orders, If the order cannot be processed. false - no orders will be imported, if
     * someone invalid true - invalid orders will be skipped, valid orders will be imported
     */
    public bool $skipInvalidOrders = false;

    public function setSkipInvalidOrders(bool $skipInvalidOrders): self
    {
        $this->skipInvalidOrders = $skipInvalidOrders;
        return $this;
    }

    /**
     * @return array{skip_invalid_orders: bool}
     */
    public function toArray(): array
    {
        return [
            'skip_invalid_orders' => $this->skipInvalidOrders,
        ];
    }
}
