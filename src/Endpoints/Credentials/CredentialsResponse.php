<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Credentials;

use SmartEmailing\v3\Endpoints\AbstractResponse;
use SmartEmailing\v3\Exceptions\PropertyRequiredException;

class CredentialsResponse extends AbstractResponse
{
    protected ?int $accountId = null;

    /**
     * Current account id
     */
    public function accountId(): int
    {
        if ($this->accountId === null) {
            throw new PropertyRequiredException('accountId');
        }
        return $this->accountId;
    }

    /**
     * Setups the final data
     *
     * @return $this
     */
    protected function setupData()
    {
        parent::setupData();
        return $this->set('account_id', 'accountId');
    }
}
