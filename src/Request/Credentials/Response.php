<?php
namespace SmartEmailing\v3\Request\Credentials;

use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Request\Response as BaseRequest;

class Response extends BaseRequest
{
    /**
     * @var int
     */
    protected $accountId;

    /**
     * Setups the final data
     * @return $this
     */
    protected function setupData()
    {
        parent::setupData();
        return $this->set('account_id', 'accountId');
    }

    /**
     * Current account id
     * @return int
     */
    public function accountId()
    {
        return $this->accountId;
    }

}