<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Send;

use SmartEmailing\v3\Endpoints\AbstractRequest;
use SmartEmailing\v3\Endpoints\StatusResponse;
use SmartEmailing\v3\Models\SenderCredentials;
use SmartEmailing\v3\Models\Task;

/**
 * @link https://app.smartemailing.cz/docs/api/v3/index.html#api-Custom_campaigns-Send_transactional_emails
 *
 * @extends AbstractRequest<StatusResponse>
 */
abstract class AbstractSendRequest extends AbstractRequest
{
    /**
     * @var SenderCredentials
     */
    protected $senderCredentials;

    /**
     * @var int
     */
    protected $emailId;

    /**
     * @var string
     */
    protected $tag;

    /**
     * @var Task[]
     */
    protected $tasks = [];

    /**
     * @return int
     */
    public function getEmailId(): ?int
    {
        return $this->emailId;
    }

    public function setEmailId(int $emailId): void
    {
        $this->emailId = $emailId;
    }

    /**
     * @return string
     */
    public function getTag(): ?String
    {
        return $this->tag;
    }

    public function setTag(string $tag): void
    {
        $this->tag = $tag;
    }

    /**
     * @return SenderCredentials
     */
    public function getSenderCredentials(): ?SenderCredentials
    {
        return $this->senderCredentials;
    }

    public function setSenderCredentials(SenderCredentials $senderCredentials): void
    {
        $this->senderCredentials = $senderCredentials;
    }

    public function addTask(Task $task): void
    {
        $this->tasks[] = $task;
    }

    /**
     * @return Task[]
     */
    public function getTasks(): array
    {
        return $this->tasks;
    }

    protected function method(): string
    {
        return 'POST';
    }
}
