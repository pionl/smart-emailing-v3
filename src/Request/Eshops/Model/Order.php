<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Request\Eshops\Model;

use SmartEmailing\v3\Exceptions\InvalidFormatException;
use function SmartEmailing\v3\Helpers\convertDate;
use SmartEmailing\v3\Models\Model;
use SmartEmailing\v3\Request\Eshops\Model\Holder\Attributes;
use SmartEmailing\v3\Request\Eshops\Model\Holder\FeedItems;
use SmartEmailing\v3\Request\Eshops\Model\Holder\OrderItems;

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

    //region Properties
    /**
     * E-mail address of imported order. This is the only required field.
     *
     * @var string|null required
     */
    public $emailAddress = null;

    /**
     * @var  string|null required
     */
    public $eshopName = null;

    /**
     * @var string|null required
     */
    public $eshopCode = null;

    /**
     * Format YYYY-MM-DD HH:MM:SS
     *
     * @var string|null
     */
    protected $createdAt = null;

    /**
     * Format YYYY-MM-DD HH:MM:SS
     *
     * @var null|string
     */
    protected $paidAt = null;

    /**
     * Status of order (defaults to placed when not specified). Available values are placed, processing, shipped,
     * cancelled, unknown.
     *
     * @var string
     */
    protected $status = self::STATUS_PLACED;

    /**
     * @var Attributes
     */
    protected $attributes;

    /**
     * @var OrderItems
     */
    protected $orderItems;

    /**
     * @var FeedItems
     */
    protected $feedItems;

    //endregion

    public function __construct($eshopName, $eshopCode, $emailAddress)
    {
        $this->eshopName = $eshopName;
        $this->eshopCode = $eshopCode;
        $this->emailAddress = $emailAddress;
        $this->orderItems = new OrderItems();
        $this->attributes = new Attributes();
        $this->feedItems = new FeedItems();
    }

    //region Setters

    /**
     * @param null|string $emailAddress
     */
    public function setEmailAddress($emailAddress): self
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    /**
     * @param mixed $eshopName
     */
    public function setEshopName($eshopName): self
    {
        $this->eshopName = $eshopName;
        return $this;
    }

    /**
     * @param mixed $eshopCode
     */
    public function setEshopCode($eshopCode): self
    {
        $this->eshopCode = $eshopCode;
        return $this;
    }

    /**
     * @param string $createdAt
     * @param bool $convertToValidFormat converts the value to valid format
     */
    public function setCreatedAt($createdAt, $convertToValidFormat = true): self
    {
        $this->createdAt = convertDate($createdAt, $convertToValidFormat);
        return $this;
    }

    /**
     * @param string $paidAt
     * @param bool $convertToValidFormat converts the value to valid format
     */
    public function setPaidAt($paidAt, $convertToValidFormat = true): self
    {
        $this->paidAt = convertDate($paidAt, $convertToValidFormat);
        return $this;
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

    //endregion

    /**
     * Converts data to array
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
}
