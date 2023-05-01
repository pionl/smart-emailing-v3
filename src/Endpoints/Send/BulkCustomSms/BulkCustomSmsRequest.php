<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Send\BulkCustomSms;

use SmartEmailing\v3\Endpoints\Send\AbstractSendRequest;
use SmartEmailing\v3\Endpoints\StatusResponse;
use SmartEmailing\v3\Exceptions\PropertyRequiredException;

/**
 * @link https://app.smartemailing.cz/docs/api/v3/index.html#api-Custom_campaigns-Send_bulk_custom_SMS
 */
class BulkCustomSmsRequest extends AbstractSendRequest
{
    /**
     * The maximum tasks per single request Single request is restricted to contain 500 SMS at most. Multiple
     * simultaneous calls is allowed.
     *
     * @var int
     */
    protected $chunkLimit = 500;

    /**
     * @var int|null
     */
    protected $smsId;

    /**
     * Will send multiple requests because of the 500 count limit
     */
    public function send(): StatusResponse
    {
        // There is not enough contacts to enable chunk mode
        if ($this->chunkLimit >= count($this->getTasks())) {
            return parent::send();
        }

        return $this->sendInChunkMode();
    }

    public function getSmsId(): ?int
    {
        return $this->smsId;
    }

    public function setSmsId(int $smsId)
    {
        $this->smsId = $smsId;
    }

    /**
     * Converts data to array
     */
    public function toArray(): array
    {
        foreach ($this->getTasks() as $task) {
            PropertyRequiredException::throwIf(
                'cellphone',
                $task->getRecipient() !== null && empty($task->getRecipient()->getCellphone()) === false,
                'You must set cellphone for recipient - missing cellphone'
            );
        }

        return [
            'tag' => $this->getTag(),
            'sms_id' => $this->getSmsId(),
            'tasks' => $this->getTasks(),
        ];
    }

    /**
     * Sends tasks in chunk mode
     */
    protected function sendInChunkMode(): StatusResponse
    {
        $originalFullTaskList = $this->getTasks();
        $lastResponse = null;

        foreach (array_chunk($this->getTasks(), $this->chunkLimit) as $tasks) {
            $this->tasks = $tasks;

            $lastResponse = parent::send();
        }

        $this->tasks = $originalFullTaskList;

        return $lastResponse;
    }

    protected function endpoint(): string
    {
        return 'send/custom-sms-bulk';
    }
}
