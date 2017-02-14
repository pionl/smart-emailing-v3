<?php
namespace SmartEmailing\v3\Request\Ping;

use SmartEmailing\v3\Request\AbstractRequest;

class Ping extends AbstractRequest
{
    protected function endpoint()
    {
        return 'ping';
    }

    protected function options()
    {
        return [];
    }
}