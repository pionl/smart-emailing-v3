<?php
namespace SmartEmailing\v3\Tests\Mock;

use SmartEmailing\v3\Models\AbstractHolder;

class HolderMock extends AbstractHolder
{
    public function add($item)
    {
        $this->items[] = $item;
        return $this;
    }
}
