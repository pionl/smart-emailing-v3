<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints;

class AbstractFilters
{
    /**
     * @var array<string, mixed>
     */
    protected array $filters = [];

    public function toArray(): array
    {
        return array_filter($this->filters, static fn ($value): bool => $value !== null);
    }
}
