<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Request\Newsletter;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Request\AbstractRequest;

class Newsletter extends AbstractRequest implements \JsonSerializable
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

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    protected function endpoint(): string
    {
        return 'newsletter';
    }

    protected function method(): string
    {
        return 'POST';
    }

    protected function options(): array
    {
        return [
            'json' => $this->jsonSerialize(),
        ];
    }

    private function toArray(): array
    {
        return [
            'email_id' => $this->emailId,
            'contactlists' => $this->contactLists,
        ];
    }
}
