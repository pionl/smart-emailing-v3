<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Mock;

use SmartEmailing\v3\Endpoints\AbstractRequest;

class RequestMock extends AbstractRequest
{
    public function toArray(): array
    {
        return [];
    }

    protected function endpoint(): string
    {
        return 'endpoint';
    }

    /**
     * @return array{json: string[]}
     */
    protected function options(): array
    {
        return [
            'json' => ['test'],
        ];
    }
}
