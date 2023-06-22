<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

use SmartEmailing\v3\Exceptions\PropertyRequiredException;

class Replace extends Model
{
    protected ?string $key = null;

    protected ?string $content = null;

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(String $key): self
    {
        $this->key = $key;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(String $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return array{key: string|null, content: string|null}
     */
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
}
