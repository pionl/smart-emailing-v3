<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\CustomRequest;

use SmartEmailing\v3\Endpoints\AbstractDataResponse;

/**
 * @extends AbstractDataResponse<\stdClass|array<\stdClass>>
 */
class CustomResponse extends AbstractDataResponse
{
    protected function setupData()
    {
        parent::setupData();
        $this->data = $this->value($this->json, 'data');
        return $this;
    }
}
