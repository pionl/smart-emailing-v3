<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

use SmartEmailing\v3\Exceptions\PropertyRequiredException;

class SenderCredentials extends Model
{
    protected ?string $from = null;

    protected ?string $replyTo = null;

    protected ?string $senderName = null;

    public function getFrom(): ?string
    {
        return $this->from;
    }

    public function setFrom(String $from): self
    {
        $this->from = $from;
        return $this;
    }

    public function getReplyTo(): ?string
    {
        return $this->replyTo;
    }

    public function setReplyTo(String $replyTo): self
    {
        $this->replyTo = $replyTo;
        return $this;
    }

    public function getSenderName(): ?string
    {
        return $this->senderName;
    }

    public function setSenderName(String $senderName): self
    {
        $this->senderName = $senderName;
        return $this;
    }

    /**
     * @return array{from: string|null, reply_to: string|null, sender_name: string|null}
     */
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
