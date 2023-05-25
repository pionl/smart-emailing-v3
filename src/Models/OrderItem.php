<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

use SmartEmailing\v3\Models\Holder\Attributes;

/**
 * OrderItem wrapper with public properties (allows force set and easy getter). The fluent setter will help to set
 * values in correct format.
 */
class OrderItem extends Model
{
    /**
     * @var string|null required
     */
    public ?string $id = null;

    /**
     * @var string|null required
     */
    public ?string $name = null;

    public ?string $description = null;

    /**
     * @var Price required
     */
    public Price $price;

    /**
     * @var int required
     */
    public int $quantity = 0;

    public ?string $unit = null;

    /**
     * @var string required
     */
    public string $url = '';

    public ?string $image_url = null;

    protected Attributes $attributes;

    /**
     * @param int|string $id
     */
    public function __construct($id, string $name, int $quantity, Price $price, string $url)
    {
        $this->setId($id);
        $this->setName($name);
        $this->setQuantity($quantity);
        $this->setPrice($price);
        $this->setUrl($url);
        $this->attributes = new Attributes();
    }

    /**
     * @param int|string $id
     */
    public function setId($id): self
    {
        $this->id = (string) $id;
        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function setPrice(Price $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function setQuantity(int $quantity, string $unit = null): self
    {
        $this->quantity = $quantity;
        $this->unit = $unit;
        return $this;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function setImageUrl(?string $image_url): self
    {
        $this->image_url = $image_url;
        return $this;
    }

    public function attributes(): Attributes
    {
        return $this->attributes;
    }

    /**
     * @return array{id: string|null, name: string|null, description: string|null, price: Price, quantity: int, unit: string, url: string, image_url: string|null, attributes: Attributes}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'unit' => $this->unit ?? 'pieces',
            'url' => $this->url,
            'image_url' => $this->image_url,
            'attributes' => $this->attributes,
        ];
    }
}
