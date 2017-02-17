<?php
namespace SmartEmailing\v3\Tests\Mock;

use SmartEmailing\v3\Models\Model;

class ModelMock extends Model
{
    public function toArray()
    {
        return [
            'null' => null,
            'boolean' => true,
            'string' => 'hello',
            'empty' => [],
            'array' => ['hello'],
            'holder' => (new HolderMock())->add('hello'),
            'holder_empty' => new HolderMock()
        ];
    }

}