<?php
namespace SmartEmailing\v3\Request\Import\Holder;

abstract class AbstractHolder implements \JsonSerializable
{
    /**
     * A list of items
     * @var array
     */
    protected $items = [];

    /**
     * @param $index
     *
     * @return mixed
     */
    public function get($index)
    {
        return $this->items[$index];
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

    public function toArray()
    {
        return $this->items;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }


}