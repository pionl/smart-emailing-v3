<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Credentials;

use SmartEmailing\v3\Endpoints\AbstractResponse;

class CredentialsResponse extends AbstractResponse
{
    /**
     * @var int
     */
    protected $accountId;

    /**
     * Current account id
     *
     * @return int|null
     */
    public function accountId()
    {
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
