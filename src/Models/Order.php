<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Models\Holder\Attributes;
use SmartEmailing\v3\Models\Holder\FeedItems;
use SmartEmailing\v3\Models\Holder\OrderItems;

/**
 * Order wrapper with public properties (allows force set and easy getter). The fluent setter will help to set values in
 * correct format.
 */
class Order extends Model
{
    public const STATUS_PLACED = 'placed';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_UNKNOWN = 'unknown';

    /**
     * E-mail address of imported order. This is the only required field.
     *
     * @var string|null required
     */
    protected ?string $emailAddress = null;

    /**
     * @var  string|null required
     */
    protected ?string $eshopName = null;

    /**
     * @var string|null required
     */
    protected ?string $eshopCode = null;

    /**
     * Format YYYY-MM-DD HH:MM:SS
     */
    protected ?string $createdAt = null;

    /**
     * Format YYYY-MM-DD HH:MM:SS
     */
    protected ?string $paidAt = null;

    /**
     * Status of order (defaults to placed when not specified). Available values are placed, processing, shipped,
     * cancelled, unknown.
     */
    protected string $status = self::STATUS_PLACED;

    private Attributes $attributes;

    private OrderItems $orderItems;

    private FeedItems $feedItems;

    public function __construct(?string $eshopName, ?string $eshopCode, ?string $emailAddress)
    {
        $this->eshopName = $eshopName;
        $this->eshopCode = $eshopCode;
        $this->emailAddress = $emailAddress;
        $this->orderItems = new OrderItems();
        $this->attributes = new Attributes();
        $this->feedItems = new FeedItems();
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(?string $emailAddress): self
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    public function getEshopName(): ?string
    {
        return $this->eshopName;
    }

    public function setEshopName(?string $eshopName): self
    {
        $this->eshopName = $eshopName;
        return $this;
    }

    public function getEshopCode(): ?string
    {
        return $this->eshopCode;
    }

    public function setEshopCode(?string $eshopCode): self
    {
        $this->eshopCode = $eshopCode;
        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    /**
     * @param bool $convertToValidFormat converts the value to valid format
     */
    public function setCreatedAt(?string $createdAt, bool $convertToValidFormat = true): self
    {
        $this->createdAt = $this->convertDate($createdAt, $convertToValidFormat);
        return $this;
    }

    public function getPaidAt(): ?string
    {
        return $this->paidAt;
    }

    /**
     * @param bool $convertToValidFormat converts the value to valid format
     */
    public function setPaidAt(?string $paidAt, bool $convertToValidFormat = true): self
    {
        $this->paidAt = $this->convertDate($paidAt, $convertToValidFormat);
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        InvalidFormatException::checkInArray($status, [
            self::STATUS_PLACED,
            self::STATUS_CANCELED,
            self::STATUS_PROCESSING,
            self::STATUS_SHIPPED,
            self::STATUS_UNKNOWN,
        ]);
        $this->status = $status;
        return $this;
    }

    public function orderItems(): OrderItems
    {
        return $this->orderItems;
    }

    public function attributes(): Attributes
    {
        return $this->attributes;
    }

    public function feedItems(): FeedItems
    {
        return $this->feedItems;
    }

    /**
     * @return array{emailaddress: string|null, eshop_name: string|null, eshop_code: string|null, status: string, paid_at: string|null, created_at: string|null, attributes: Attributes, items: OrderItems, item_feeds: FeedItems}
     */
    public function toArray(): array
    {
        return [
            'emailaddress' => $this->emailAddress,
            'eshop_name' => $this->eshopName,
            'eshop_code' => $this->eshopCode,
            'status' => $this->status,
            'paid_at' => $this->paidAt,
            'created_at' => $this->createdAt,
            'attributes' => $this->attributes,
            'items' => $this->orderItems,
            'item_feeds' => $this->feedItems,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->removeEmptyValues($this->toArray());
    }
}
