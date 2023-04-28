<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Request\Eshops\Model;

use SmartEmailing\v3\Models\Model;

/**
 * Price wrapper with public properties (allows force set and easy getter). The fluent setter will help to set values in
 * correct format.
 */
class Price extends Model
{
    /**
     * @var number required
     */
    public $withoutVat;

    /**
     * @var number required
     */
    public $withVat;

    /**
     * item price currency code (ISO-4217 three-letter ("Alpha-3")) i.e.: CZK, EUR
     *
     * @var string required
     */
    protected $currency;

    public function __construct($withoutVat, $withVat, $currency)
    {
        $this->withoutVat = $withoutVat;
        $this->withVat = $withVat;
        $this->currency = $currency;
    }

    /**
     * @param number $withoutVat
     */
    public function setWithoutVat($withoutVat): self
    {
        $this->withoutVat = $withoutVat;
        return $this;
    }

    /**
     * @param number $withVat
     */
    public function setWithVat($withVat): self
    {
        $this->withVat = $withVat;
        return $this;
    }

    /**
     * item price currency code (ISO-4217 three-letter ("Alpha-3")) i.e.: CZK, EUR
     */
    public function setCurrency(string $currency): self
    {
        $this->currency = substr(strtoupper($currency), 0, 3);
        return $this;
    }

    /**
     * Converts data to array
     */
    public function toArray(): array
    {
        return [
            'without_vat' => $this->withoutVat,
            'with_vat' => $this->withVat,
            'currency' => $this->currency,
        ];
    }

    public function jsonSerialize(): array
    {
        // Don't remove null/empty values - not needed
        return $this->toArray();
    }
}
