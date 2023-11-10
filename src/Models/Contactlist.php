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

    protected ?int $id = null;

    /**
     * @var string|null Contactlist name.
     */
    protected ?string $name = null;

    protected ?string $category = null;

    /**
     * @var string|null Contactlist public name that will be displayed in unsubscribe pages.
     */
    protected ?string $publicName = null;

    /**
     * @var string|null Name of contactlist owner.
     */
    protected ?string $senderName = null;

    protected ?string $senderEmail = null;

    /**
     * @var string|null Email address for list reply-to.
     */
    protected ?string $replyTo = null;

    /**
     * @var float|null Percentage of link-clicking contacts in list or null if there are is no data.
     */
    protected ?float $clickRate = null;

    /**
     * @var float|null Percentage of email-opening contacts in list or null if there are is no data.
     */
    protected ?float $openRate = null;

    /**
     * @var int|null Total count of contacts in list.
     */
    protected ?int $alertOut = null;

    /**
     * @var int|null ID of supervising Segment. null if there is none.
     */
    protected ?int $segmentId = null;

    /**
     * @var string|null Sender signature. This can be used as customfield in your e-mails.
     */
    protected ?string $signature = null;

    /**
     * @var string|null Custom contactlist notes.
     */
    protected ?string $notes = null;

    /**
     * @var string|null Date and time of contactlist creation in YYYY-MM-DD HH:MM:SS format.
     */
    protected ?string $created = null;

    /**
     * @param int|numeric-string|null $id
     */
    public function __construct($id = null)
    {
        $this->setId($id);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|numeric-string|null $id
     */
    public function setId($id): self
    {
        $this->id = $id !== null ? (int) $id : null;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function getPublicName(): ?string
    {
        return $this->publicName;
    }

    public function setPublicName(?string $publicName): self
    {
        $this->publicName = $publicName;
        return $this;
    }

    public function getSenderName(): ?string
    {
        return $this->senderName;
    }

    public function setSenderName(?string $senderName): self
    {
        $this->senderName = $senderName;
        return $this;
    }

    public function getSenderEmail(): ?string
    {
        return $this->senderEmail;
    }

    public function setSenderEmail(?string $senderEmail): self
    {
        $this->senderEmail = $senderEmail;
        return $this;
    }

    public function getReplyTo(): ?string
    {
        return $this->replyTo;
    }

    public function setReplyTo(?string $replyTo): self
    {
        $this->replyTo = $replyTo;
        return $this;
    }

    public function getClickRate(): ?float
    {
        return $this->clickRate;
    }

    public function getOpenRate(): ?float
    {
        return $this->openRate;
    }

    public function getAlertOut(): ?int
    {
        return $this->alertOut;
    }

    public function getSegmentId(): ?int
    {
        return $this->segmentId;
    }

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function getCreated(): ?string
    {
        return $this->created;
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

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return $this->removeEmptyValues($this->toArray());
    }
}
