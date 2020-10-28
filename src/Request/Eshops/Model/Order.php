<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Eshops\Model;

use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Request\Eshops\Model\Holder\Attributes;
use SmartEmailing\v3\Request\Eshops\Model\Holder\FeedItems;
use SmartEmailing\v3\Request\Eshops\Model\Holder\OrderItems;
use function \SmartEmailing\v3\Helpers\convertDate;
use SmartEmailing\v3\Models\Model;

/**
 * Order wrapper with public properties (allows force set and easy getter). The fluent setter will help
 * to set values in correct format.
 *
 * @package SmartEmailing\v3\Request\Eshops\Model
 */
class Order extends Model
{
	const STATUS_PLACED = 'placed';
	const STATUS_PROCESSING = 'processing';
	const STATUS_SHIPPED = 'shipped';
	const STATUS_CANCELLED = 'cancelled';
	const STATUS_UNKNOWN = 'unknown';
	//region Properties
	/**
	 * E-mail address of imported order. This is the only required field.
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
	 * @var string|null
	 */
	protected $createdAt = null;
	/**
	 * Format YYYY-MM-DD HH:MM:SS
	 * @var null|string
	 */
	protected $paidAt = null;
	/**
	 * Status of order (defaults to placed when not specified).
	 * Available values are placed, processing, shipped, cancelled, unknown.
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

	/**
	 * Order constructor.
	 * @param $eshopName
	 * @param $eshopCode
	 * @param $emailAddress
	 */
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
	 *
	 * @return Order
	 */
	public function setEmailAddress($emailAddress): Order
	{
		$this->emailAddress = $emailAddress;
		return $this;
	}

	/**
	 * @param mixed $eshopName
	 * @return Order
	 */
	public function setEshopName($eshopName): Order
	{
		$this->eshopName = $eshopName;
		return $this;
	}

	/**
	 * @param mixed $eshopCode
	 * @return Order
	 */
	public function setEshopCode($eshopCode): Order
	{
		$this->eshopCode = $eshopCode;
		return $this;
	}

	/**
	 * @param string $createdAt
	 * @param bool $convertToValidFormat converts the value to valid format
	 * @return Order
	 */
	public function setCreatedAt($createdAt, $convertToValidFormat = true): Order
	{
		$this->createdAt = convertDate($createdAt, $convertToValidFormat);
		return $this;
	}

	/**
	 * @param string $paidAt
	 * @param bool $convertToValidFormat converts the value to valid format
	 * @return Order
	 */
	public function setPaidAt($paidAt, $convertToValidFormat = true): Order
	{
		$this->paidAt = convertDate($paidAt, $convertToValidFormat);
		return $this;
	}

	/**
	 * @param string|null $status
	 * @return Order
	 */
	public function setStatus(string $status): Order
	{
		InvalidFormatException::checkInArray($status, [
			self::STATUS_PLACED,
			self::STATUS_CANCELLED,
			self::STATUS_PROCESSING,
			self::STATUS_SHIPPED,
			self::STATUS_UNKNOWN
		]);
		$this->status = $status;
		return $this;
	}

	/**
	 * @return OrderItems
	 */
	public function orderItems(): OrderItems
	{
		return $this->orderItems;
	}

	/**
	 * @return Attributes
	 */
	public function attributes(): Attributes
	{
		return $this->attributes;
	}

	/**
	 * @return FeedItems
	 */
	public function feedItems(): FeedItems
	{
		return $this->feedItems;
	}

	//endregion

	/**
	 * Converts data to array
	 * @return array
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
