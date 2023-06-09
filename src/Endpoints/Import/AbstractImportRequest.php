<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Import;

use SmartEmailing\v3\Endpoints\AbstractRequest;
use SmartEmailing\v3\Endpoints\StatusResponse;
use SmartEmailing\v3\Models\Model;

/**
 * @extends AbstractRequest<StatusResponse>
 */
abstract class AbstractImportRequest extends AbstractRequest
{
    /**
     * The maximum contacts per single request
     *
     * @var int<1, 500>
     */
    public int $chunkLimit = 500;

    protected array $data = [];

    /**
     * Will send multiple requests because of the 500 count limit
     */
    public function send(): StatusResponse
    {
        // There is not enough contacts to enable chunk mode
        if ($this->chunkLimit >= count($this->data)) {
            return parent::send();
        }

        $response = $this->sendInChunkMode();
        if ($response instanceof StatusResponse === false) {
            throw new \Exception('Response is null');
        }
        return $response;
    }

    /**
     * @return array{settings: Model, data: mixed[]}
     */
    public function toArray(): array
    {
        return [
            'settings' => $this->settings(),
            'data' => $this->data,
        ];
    }

    abstract public function settings(): Model;

    /**
     * Sends contact list in chunk mode
     */
    protected function sendInChunkMode(): ?StatusResponse
    {
        // Store the original contact list
        $originalFull = $this->data;
        $lastResponse = null;

        // Chunk the array of contacts send it in multiple requests
        foreach (array_chunk($this->data, $this->chunkLimit) as $data) {
            // Store the contacts that will be passed
            $this->data = $data;

            $lastResponse = parent::send();
        }

        // Restore to original array
        $this->data = $originalFull;

        return $lastResponse;
    }

    protected function method(): string
    {
        return 'POST';
    }
}
