<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Mock;

use SmartEmailing\v3\Models\Model;

class ModelMock extends Model
{
    public $boolean = true;

    public $null = null;

    public $array = [];

    public function toArray()
    {
        return [
            'null' => $this->null,
            'boolean' => $this->boolean,
            'string' => 'hello',
            'empty' => $this->array,
            'array' => ['hello'],
            'holder' => (new HolderMock())->add('hello'),
            'holder_empty' => new HolderMock(),
        ];
    }
}
