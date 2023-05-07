<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Models;

use PHPUnit\Framework\TestCase;
use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Models\Replace;

class ReplaceTest extends TestCase
{
    private Replace $model;

    protected function setUp(): void
    {
        $this->model = new Replace();
    }

    public function testShouldThrowExceptionWhenMissingAllData(): void
    {
        $this->expectException(PropertyRequiredException::class);
        $this->model->toArray();
    }

    public function testShouldThrowExceptionWhenMissingPartialData(): void
    {
        $this->model->setContent('data');
        $this->expectException(PropertyRequiredException::class);
        $this->model->toArray();
    }

    public function testShouldReturnArrayWithData(): void
    {
        $this->model->setKey('key');
        $this->model->setContent('value');

        $data = $this->model->toArray();

        self::assertSame([
            'key' => 'key',
            'content' => 'value',
        ], $data);
    }

    public function testShouldReturnSameDataFromSerializer(): void
    {
        $this->model->setKey('key');
        $this->model->setContent('value');

        self::assertSame($this->model->toArray(), $this->model->jsonSerialize());
    }

    public function testShouldSetSettersAndReadGetters(): void
    {
        $this->model->setKey('key');
        self::assertSame('key', $this->model->getKey());

        $this->model->setContent('value');
        self::assertSame('value', $this->model->getContent());
    }
}
