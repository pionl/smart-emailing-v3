<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

use SmartEmailing\v3\Exceptions\PropertyRequiredException;

class Recipient extends Model
{
    /**
     * @var string
     */
    private ?string $emailAddress = null;

    private string $cellphone = '';

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(string $emailAddress): void
    {
        $this->emailAddress = $emailAddress;
    }

    public function getCellphone(): string
    {
        return $this->cellphone;
    }

    public function setCellphone(string $cellphone): void
    {
        $this->cellphone = $cellphone;
    }

    public function toArray(): array
    {
        PropertyRequiredException::throwIf(
            'emailaddress',
            empty($this->getEmailAddress()) === false,
            'You must set emailaddress - missing emailaddress'
        );

        $data = [
            'emailaddress' => $this->getEmailAddress(),
        ];

        if (empty($this->getCellphone()) === false) {
            $data['cellphone'] = $this->getCellphone();
        }

        return $data;
    }
}
