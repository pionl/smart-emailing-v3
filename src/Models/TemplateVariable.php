<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

class TemplateVariable extends Model
{
    private array $customData = [];

    public function getCustomData(): array
    {
        return $this->customData;
    }

    public function setCustomData(array $customData): self
    {
        $this->customData = $customData;
        return $this;
    }

    public function toArray(): array
    {
        return $this->getCustomData();
    }
}
