<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Request\Send;

/**
 * Class BulkCustomEmails
 *
 * @link https://app.smartemailing.cz/docs/api/v3/index.html#api-Custom_campaigns-Send_bulk_custom_emails
 */
class BulkCustomEmails extends AbstractSend
{
    /**
     * Converts data to array
     */
    public function toArray(): array
    {
        return [
            'sender_credentials' => $this->getSenderCredentials(),
            'tag' => $this->getTag(),
            'email_id' => $this->getEmailId(),
            'tasks' => $this->getTasks(),
        ];
    }

    protected function endpoint(): string
    {
        return 'send/custom-emails-bulk';
    }
}
