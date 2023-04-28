<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Request\Contactlists;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Request\AbstractRequest;

class ContactlistTruncate extends AbstractRequest
{
    private int $listId;

    public function __construct(Api $api, int $listId)
    {
        parent::__construct($api);

        $this->listId = $listId;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    protected function method(): string
    {
        return 'POST';
    }

    protected function endpoint(): string
    {
        return 'contactlists/' . $this->listId . '/truncate';
    }

    protected function options(): array
    {
        return [
            'json' => $this->jsonSerialize(),
        ];
    }

    private function toArray(): array
    {
        return [];
    }
}
