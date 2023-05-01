<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\CustomRequest;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Endpoints\AbstractRequest;

/**
 * @extends AbstractRequest<CustomResponse>
 */
class CustomRequest extends AbstractRequest
{
    private string $action;

    private string $method;

    private array $postData;

    public function __construct(Api $api, string $action, string $method = 'GET', array $postData = [])
    {
        parent::__construct($api);
        $this->action = $action;
        $this->method = $method;
        $this->postData = $postData;
    }

    public function toArray(): array
    {
        return $this->postData;
    }

    protected function method(): string
    {
        return $this->method;
    }

    protected function endpoint()
    {
        return $this->action;
    }
}
