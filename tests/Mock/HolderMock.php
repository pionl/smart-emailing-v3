<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Mock;

use SmartEmailing\v3\Models\AbstractHolder;
use SmartEmailing\v3\Models\Model;

/**
 * @extends AbstractHolder<Model>
 */
class HolderMock extends AbstractHolder
{
    public function add(Model $item): self
    {
        $this->items[] = $item;
        return $this;
    }
}
