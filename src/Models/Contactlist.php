<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

class Contactlist extends Model
{
    /**
     * Options allowed in select parameter of query
     */
    public const SELECT_FIELDS = [
        'id',
        'name',
        'category',
        'publicname',
        'sendername',
        'senderemail',
        'replyto',
        'signature',
        'segment_id',
    ];

    public ?int $id = null;

    /**
     * @var string|null Contactlist name.
     */
    public ?string $name = null;

    public ?string $category = null;

    /**
     * @var string|null Contactlist public name that will be displayed in unsubscribe pages.
     */
    public ?string $publicName = null;

    /**
     * @var string|null Name of contactlist owner.
     */
    public ?string $senderName = null;

    public ?string $senderEmail = null;

    /**
     * @var int|null Email address for list reply-to.
     */
    public ?int $replyTo = null;

    /**
     * @var float|null Percentage of link-clicking contacts in list or null if there are is no data.
     */
    public ?float $clickRate = null;

    /**
     * @var float|null Percentage of email-opening contacts in list or null if there are is no data.
     */
    public ?float $openRate = null;

    /**
     * @var int|null Total count of contacts in list.
     */
    public ?int $alertOut = null;

    /**
     * @var int|null ID of supervising Segment. null if there is none.
     */
    public ?int $segmentId = null;

    /**
     * @var string|null Sender signature. This can be used as customfield in your e-mails.
     */
    public ?string $signature = null;

    /**
     * @var string|null Custom contactlist notes.
     */
    public ?string $notes = null;

    /**
     * @var string|null Date and time of contactlist creation in YYYY-MM-DD HH:MM:SS format.
     */
    public ?string $created = null;

    /**
     * @param int|numeric-string|null $id
     */
    public function __construct($id)
    {
        $this->setId($id);
    }

    /**
     * @param int|numeric-string|null $id
     */
    public function setId($id): self
    {
        $this->id = $id !== null ? (int) $id : null;
        return $this;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setPublicName(?string $publicName): self
    {
        $this->publicName = $publicName;
        return $this;
    }

    public function setSenderName(?string $senderName): self
    {
        $this->senderName = $senderName;
        return $this;
    }

    public function setSenderEmail(?string $senderEmail): self
    {
        $this->senderEmail = $senderEmail;
        return $this;
    }

    public function setReplyTo(?int $replyTo): self
    {
        $this->replyTo = $replyTo;
        return $this;
    }

    /**
     * @return array{id: int|null}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'publicname' => $this->publicName,
            'sendername' => $this->senderName,
            'senderemail' => $this->senderEmail,
            'replyto' => $this->replyTo,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->removeEmptyValues($this->toArray());
    }
}
