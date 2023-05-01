<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Newsletter;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Endpoints\AbstractRequest;
use SmartEmailing\v3\Endpoints\IdentifierResponse;

/**
 * @extends AbstractRequest<IdentifierResponse>
 */
class NewsletterRequest extends AbstractRequest
{
    private int $emailId;

    /**
     * @var int[]
     */
    private array $contactLists = [];

    public function __construct(Api $api, int $emailId, array $contactLists)
    {
        parent::__construct($api);
        $this->emailId = $emailId;
        $this->contactLists = $contactLists;
    }

    public function toArray(): array
    {
        return [
            'email_id' => $this->emailId,
            'contactlists' => $this->contactLists,
        ];
    }

    protected function endpoint(): string
    {
        return 'newsletter';
    }

    protected function method(): string
    {
        return 'POST';
    }
}
