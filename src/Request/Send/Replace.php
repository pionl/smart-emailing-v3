<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Request\Send;

use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Models\Model;

class Replace extends Model
{
    private ?string $key = null;

    private ?string $content = null;

    public function getKey(): ?String
    {
        return $this->key;
    }

    public function setKey(String $key): void
    {
        $this->key = $key;
    }

    public function getContent(): ?String
    {
        return $this->content;
    }

    public function setContent(String $content): void
    {
        $this->content = $content;
    }

    public function toArray(): array
    {
        PropertyRequiredException::throwIf(
            'key',
            empty($this->getKey()) === false,
            'You must set key - missing key'
        );
        PropertyRequiredException::throwIf(
            'content',
            empty($this->getContent()) === false,
            'You must set content - missing content'
        );

        return [
            'key' => $this->getKey(),
            'content' => $this->getContent(),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
