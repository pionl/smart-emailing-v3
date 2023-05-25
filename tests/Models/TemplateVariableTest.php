<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Models;

use SmartEmailing\v3\Models\TemplateVariable;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;

class TemplateVariableTest extends BaseTestCase
{
    private TemplateVariable $model;

    protected function setUp(): void
    {
        $this->model = new TemplateVariable();
    }

    public function testShouldReturnSameDataFromSerializer(): void
    {
        $this->model->setCustomData([
            'foo' => 'bar',
        ]);

        self::assertSame($this->model->toArray(), $this->model->jsonSerialize());
    }

    public function testShouldSetSettersAndReadGetters(): void
    {
        $this->model->setCustomData([
            'foo' => 'bar',
        ]);
        self::assertSame([
            'foo' => 'bar',
        ], $this->model->getCustomData());
    }
}
