<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Mock;

use SmartEmailing\v3\Request\AbstractRequest;

class RequestMock extends AbstractRequest
{
    protected function endpoint(): string
    {
        return 'endpoint';
    }

    protected function options()
    {
        return ['test'];
    }
}
