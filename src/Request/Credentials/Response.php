<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Request\Credentials;

use SmartEmailing\v3\Request\Response as BaseRequest;

class Response extends BaseRequest
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
