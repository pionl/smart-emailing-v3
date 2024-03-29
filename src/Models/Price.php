<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

/**
 * Price wrapper with public properties (allows force set and easy getter). The fluent setter will help to set values in
 * correct format.
 */
class Price extends Model
{
    /**
     * @var float required
     */
    protected float $withoutVat;

    /**
     * @var float required
     */
    protected float $withVat;

    /**
     * item price currency code (ISO-4217 three-letter ("Alpha-3")) i.e.: CZK, EUR
     *
     * @var string required
     */
    protected string $currency;

    public function __construct(float $withoutVat, float $withVat, string $currency = 'CZK')
    {
        $this->withoutVat = $withoutVat;
        $this->withVat = $withVat;
        $this->currency = $currency;
    }

    public function getWithoutVat(): float
    {
        return $this->withoutVat;
    }

    public function setWithoutVat(float $withoutVat): self
    {
        $this->withoutVat = $withoutVat;
        return $this;
    }

    public function getWithVat(): float
    {
        return $this->withVat;
    }

    public function setWithVat(float $withVat): self
    {
        $this->withVat = $withVat;
        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
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
     * @return array{without_vat: int|float, with_vat: int|float, currency: string}
     */
    public function toArray(): array
    {
        return [
            'without_vat' => $this->withoutVat,
            'with_vat' => $this->withVat,
            'currency' => $this->currency,
        ];
    }
}
