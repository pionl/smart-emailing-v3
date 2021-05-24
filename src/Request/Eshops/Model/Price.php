<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Eshops\Model;

use SmartEmailing\v3\Models\Model;

/**
 * Price wrapper with public properties (allows force set and easy getter). The fluent setter will help
 * to set values in correct format.
 * @package SmartEmailing\v3\Request\Eshops\Model
 */
class Price extends Model
{
	/** @var number required */
	public $withoutVat;
	/** @var number required */
	public $withVat;
	/**
	 * item price currency code (ISO-4217 three-letter ("Alpha-3")) i.e.: CZK, EUR
	 * @var string required
	 */
	protected $currency;

	/**
	 * Price constructor.
	 * @param $withoutVat
	 * @param $withVat
	 * @param $currency
	 */
	public function __construct($withoutVat, $withVat, $currency)
	{
		$this->withoutVat = $withoutVat;
		$this->withVat = $withVat;
		$this->currency = $currency;
	}

	/**
	 * @param number $withoutVat
	 * @return Price
	 */
	public function setWithoutVat($withoutVat): Price
	{
		$this->withoutVat = $withoutVat;
		return $this;
	}

	/**
	 * @param number $withVat
	 * @return Price
	 */
	public function setWithVat($withVat): Price
	{
		$this->withVat = $withVat;
		return $this;
	}



	/**
	 * item price currency code (ISO-4217 three-letter ("Alpha-3")) i.e.: CZK, EUR
	 *
	 * @param string $currency
	 * @return Price
	 */
	public function setCurrency(string $currency): Price
	{
		$this->currency = substr(strtoupper($currency), 0, 3);
		return $this;
	}


	/**
	 * Converts data to array
	 * @return array
	 */
	public function toArray(): array
	{
		return [
			'without_vat' => $this->withoutVat,
			'with_vat' => $this->withVat,
			'currency' => $this->currency,
		];
	}

	/**
	 * @return array
	 */
	public function jsonSerialize(): array
	{
		// Don't remove null/empty values - not needed
		return $this->toArray();
	}
}
