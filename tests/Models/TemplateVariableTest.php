<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Models;

use PHPUnit\Framework\TestCase;
use SmartEmailing\v3\Models\TemplateVariable;

class TemplateVariableTest extends TestCase
{
    private TemplateVariable $model;

    protected function setUp(): void
    {
        $this->model = new TemplateVariable();
    }

    public function testShouldReturnSameDataFromSerializer()
    {
        $this->model->setCustomData([
            'foo' => 'bar',
        ]);

        self::assertSame($this->model->toArray(), $this->model->jsonSerialize());
    }

    public function testShouldSetSettersAndReadGetters()
    {
        $this->model->setCustomData([
            'foo' => 'bar',
        ]);
        self::assertSame([
            'foo' => 'bar',
        ], $this->model->getCustomData());
    }
}
