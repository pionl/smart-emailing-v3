<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Mock;

use SmartEmailing\v3\Models\Contact;
use SmartEmailing\v3\Models\Model;

class ModelMock extends Model
{
    public bool $boolean = true;

    public ?bool $null = null;

    public ?array $array = [];

    /**
     * @return array{null: mixed, boolean: mixed, string: string, empty: mixed, array: string[], holder: mixed, holder_empty: HolderMock}
     */
    public function toArray(): array
    {
        return [
            'null' => $this->null,
            'boolean' => $this->boolean,
            'string' => 'hello',
            'empty' => $this->array,
            'array' => ['hello'],
            'holder' => (new HolderMock())->add(new Contact('test@test.cz')),
            'holder_empty' => new HolderMock(),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->removeEmptyValues($this->toArray());
    }
}
