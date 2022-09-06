<?php
namespace SmartEmailing\v3\Request\CustomRequest;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Request\AbstractRequest;

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

    protected function method(): string
    {
        return $this->method;
    }

    protected function endpoint()
    {
        return $this->action;
    }

    protected function options()
    {
        return [
            'json' => $this->postData
        ];
    }
}
