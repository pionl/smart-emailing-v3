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

    /**
     * @var int|null
     */
    public $id = null;

    /**
     * @var string|null Contactlist name.
     */
    public $name;

    /**
     * @var string|null Contactlist public name that will be displayed in unsubscribe pages.
     */
    public $publicName;

    /**
     * @var string|null Name of contactlist owner.
     */
    public $senderName;

    /**
     * @var int|null Email address for list reply-to.
     */
    public $replyTo;

    /**
     * @var float|null Percentage of link-clicking contacts in list or null if there are is no data.
     */
    public $clickRate;

    /**
     * @var float|null Percentage of email-opening contacts in list or null if there are is no data.
     */
    public $openRate;

    /**
     * @var int|null Total count of contacts in list.
     */
    public $alertOut;

    /**
     * @var int|null ID of supervising Segment. null if there is none.
     */
    public $segmentId;

    /**
     * @var string|null Sender signature. This can be used as customfield in your e-mails.
     */
    public $signature;

    /**
     * @var string|null Custom contactlist notes.
     */
    public $notes;

    /**
     * @var string|null Date and time of contactlist creation in YYYY-MM-DD HH:MM:SS format.
     */
    public $created;

    /**
     * @param int|numeric-string|null $id
     */
    public function __construct($id)
    {
        $this->setId($id);
    }

    /**
     * @param int|numeric-string|null $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

public function setName(?string $name): void
{
    $this->name = $name;
}

    public function setPublicName(?string $publicName): void
    {
        $this->publicName = $publicName;
    }

    public function setSenderName(?string $senderName): void
    {
        $this->senderName = $senderName;
    }

    public function setReplyTo(?int $replyTo): void
    {
        $this->replyTo = $replyTo;
    }

    public function setClickRate(?float $clickRate): void
    {
        $this->clickRate = $clickRate;
    }

    public function setOpenRate(?float $openRate): void
    {
        $this->openRate = $openRate;
    }

    public function setAlertOut(?int $alertOut): void
    {
        $this->alertOut = $alertOut;
    }

    public function setSegmentId(?int $segmentId): void
    {
        $this->segmentId = $segmentId;
    }

    public function setSignature(?string $signature): void
    {
        $this->signature = $signature;
    }

    public function setNotes(?string $notes): void
    {
        $this->notes = $notes;
    }

    public function setCreated(?string $created): void
    {
        $this->created = $created;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->removeEmptyValues($this->toArray());
    }
}
