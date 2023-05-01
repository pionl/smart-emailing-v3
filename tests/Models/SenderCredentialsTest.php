<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Models;

use PHPUnit\Framework\TestCase;
use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Models\SenderCredentials;

class SenderCredentialsTest extends TestCase
{
    private SenderCredentials $model;

    protected function setUp(): void
    {
        $this->model = new SenderCredentials();
    }

    public function testShouldThrowExceptionWhenMissingAllData()
    {
        $this->expectException(PropertyRequiredException::class);
        $this->model->toArray();
    }

    public function testShouldThrowExceptionWhenMissingPartialData()
    {
        $this->model->setFrom('john@example.com');
        $this->expectException(PropertyRequiredException::class);
        $this->model->toArray();
    }

    public function testShouldReturnArrayWithData()
    {
        $this->model->setFrom('john@example.com');
        $this->model->setReplyTo('peter@example.com');
        $this->model->setSenderName('John');

        $data = $this->model->toArray();

        self::assertSame([
            'from' => 'john@example.com',
            'reply_to' => 'peter@example.com',
            'sender_name' => 'John',
        ], $data);
    }

    public function testShouldReturnSameDataFromSerializer()
    {
        $this->model->setFrom('john@example.com');
        $this->model->setReplyTo('peter@example.com');
        $this->model->setSenderName('John');

        self::assertSame($this->model->toArray(), $this->model->jsonSerialize());
    }

    public function testShouldSetSettersAndReadGetters()
    {
        $this->model->setSenderName('John');
        self::assertSame('John', $this->model->getSenderName());

        $this->model->setReplyTo('peter@example.com');
        self::assertSame('peter@example.com', $this->model->getReplyTo());

        $this->model->setFrom('john@example.com');
        self::assertSame('john@example.com', $this->model->getFrom());
    }
}
