<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Eshops;

use SmartEmailing\v3\Endpoints\StatusResponse;

/**
 * Class OrdersBulk Method is used to import orders in bulk. Up to 500 orders per request is allowed. This creates new
 * activities which can be used for scoring, segmentation and automation.
 *
 * @extends AbstractEshopOrdersRequest<StatusResponse>
 * @deprecated Use import-orders instead
 */
class EshopOrdersBulkRequest extends AbstractEshopOrdersRequest
{
    /**
     * The maximum orders per single request
     */
    public int $chunkLimit = 500;

    /**
     * Will send multiple requests because of the 500 count limit
     */
    public function send(): StatusResponse
    {
        // There is not enough contacts to enable chunk mode
        if ($this->chunkLimit >= count($this->orders)) {
            return parent::send();
        }

        return $this->sendInChunkMode();
    }

    /**
     * Sends contact list in chunk mode
     */
    protected function sendInChunkMode(): StatusResponse
    {
        // Store the original contact list
        $originalFullOrdersList = $this->orders;
        $lastResponse = null;

        // Chunk the array of contacts send it in multiple requests
        foreach (array_chunk($this->orders, $this->chunkLimit) as $orders) {
            // Store the contacts that will be passed
            $this->orders = $orders;

            $lastResponse = parent::send();
        }

        // Restore to original array
        $this->orders = $originalFullOrdersList;

        return $lastResponse;
    }

    protected function endpoint(): string
    {
        return 'orders-bulk';
    }
}
