<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Request\Eshops\Model;

use SmartEmailing\v3\Models\Model;
use SmartEmailing\v3\Request\Eshops\Model\Holder\Attributes;

/**
 * OrderItem wrapper with public properties (allows force set and easy getter). The fluent setter will help to set
 * values in correct format.
 */
class OrderItem extends Model
{
    /**
     * @var string|null required
     */
    public $id;

    /**
     * @var string|null required
     */
    public $name;

    /**
     * @var string|null
     */
    public $description;

    /**
     * @var Price required
     */
    public $price;

    /**
     * @var int required
     */
    public $quantity = 0;

    /**
     * @var string required
     */
    public $url = '';

    /**
     * @var string|null
     */
    public $image_url;

    /**
     * @var Attributes
     */
    protected $attributes;

    /**
     * OrderItem constructor.
     *
     * @param int|string $id
     * @param string $name
     * @param int $quantity
     * @param string $url
     */
    public function __construct($id, $name, $quantity, Price $price, $url)
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

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
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
     * Converts data to array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'url' => $this->url,
            'image_url' => $this->image_url,
            'attributes' => $this->attributes,
        ];
    }

    public function jsonSerialize(): array
    {
        // Don't remove null/empty values - not needed
        return $this->toArray();
    }
}
