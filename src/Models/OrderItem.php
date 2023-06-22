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
    protected ?string $id = null;

    /**
     * @var string|null required
     */
    protected ?string $name = null;

    protected ?string $description = null;

    /**
     * @var int required
     */
    protected int $quantity = 0;

    protected ?string $unit = null;

    /**
     * @var string required
     */
    protected string $url = '';

    protected ?string $image_url = null;

    /**
     * @var Price required
     */
    private Price $price;

    private Attributes $attributes;

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
        $this->price = new Price(0, 0);
        $this->attributes = new Attributes();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param int|string $id
     */
    public function setId($id): self
    {
        $this->id = (string) $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function setPrice(Price $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity, string $unit = null): self
    {
        $this->quantity = $quantity;
        $this->unit = $unit;
        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->image_url;
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
