<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Send\TransactionalEmails;

use SmartEmailing\v3\Endpoints\Send\AbstractSendRequest;
use SmartEmailing\v3\Models\MessageContents;
use SmartEmailing\v3\Models\SenderCredentials;
use SmartEmailing\v3\Models\Task;

/**
 * @link https://app.smartemailing.cz/docs/api/v3/index.html#api-Custom_campaigns-Send_transactional_emails
 */
class TransactionalEmailsRequest extends AbstractSendRequest
{
    private ?MessageContents $messageContents = null;

    public function getMessageContents(): ?MessageContents
    {
        return $this->messageContents;
    }

    public function setMessageContents(MessageContents $messageContents): void
    {
        $this->messageContents = $messageContents;
    }

    /**
     * @return array{sender_credentials: (SenderCredentials | null), tag: (string | null), email_id: (int | null), message_contents: (MessageContents | null), tasks: Task[]}
     */
    public function toArray(): array
    {
        return [
            'sender_credentials' => $this->getSenderCredentials(),
            'tag' => $this->getTag(),
            'email_id' => $this->getEmailId(),
            'message_contents' => $this->getMessageContents(),
            'tasks' => $this->getTasks(),
        ];
    }

    protected function endpoint(): string
    {
        return 'send/transactional-emails-bulk';
    }
}
