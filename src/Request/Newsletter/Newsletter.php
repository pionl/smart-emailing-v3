<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Newsletter;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Request\AbstractRequest;

class Newsletter extends AbstractRequest implements \JsonSerializable
{

    /** @var int */
    private $emailId;

    /** @var int[] */
    private $contactLists;

    /** @param int[] */
    public function __construct(Api $api, int $emailId, array $contactLists)
    {
        parent::__construct($api);
        $this->emailId = $emailId;
        $this->contactLists = $contactLists;
    }

    protected function endpoint(): string
    {
        return 'newsletter';
    }

    protected function options(): array
    {
        return [
            'json' => $this->jsonSerialize()
        ];
    }

    private function toArray(): array
    {
        return [
            'email_id' => $this->emailId,
            'contactlists' => $this->contactLists,
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

}
