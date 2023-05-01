<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

use SmartEmailing\v3\Exceptions\PropertyRequiredException;

class SenderCredentials extends Model
{
    private ?string $from = null;

    private ?string $replyTo = null;

    private ?string $senderName = null;

    public function getFrom(): ?String
    {
        return $this->from;
    }

    public function setFrom(String $from): void
    {
        $this->from = $from;
    }

    public function getReplyTo(): ?String
    {
        return $this->replyTo;
    }

    public function setReplyTo(String $replyTo): void
    {
        $this->replyTo = $replyTo;
    }

    public function getSenderName(): ?String
    {
        return $this->senderName;
    }

    public function setSenderName(String $senderName): void
    {
        $this->senderName = $senderName;
    }

    public function toArray(): array
    {
        PropertyRequiredException::throwIf(
            'from',
            empty($this->getFrom()) === false,
            'You must set from - missing from'
        );
        PropertyRequiredException::throwIf(
            'reply_to',
            empty($this->getReplyTo()) === false,
            'You must set reply_to - missing reply_to'
        );
        PropertyRequiredException::throwIf(
            'from',
            empty($this->getSenderName()) === false,
            'You must set sender_name - missing sender_name'
        );

        return [
            'from' => $this->getFrom(),
            'reply_to' => $this->getReplyTo(),
            'sender_name' => $this->getSenderName(),
        ];
    }
}
