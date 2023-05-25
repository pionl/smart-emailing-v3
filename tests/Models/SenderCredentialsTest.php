<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Models;

use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Models\SenderCredentials;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;

class SenderCredentialsTest extends BaseTestCase
{
    private SenderCredentials $model;

    protected function setUp(): void
    {
        $this->model = new SenderCredentials();
    }

    public function testShouldThrowExceptionWhenMissingAllData(): void
    {
        $this->expectException(PropertyRequiredException::class);
        $this->model->toArray();
    }

    public function testShouldThrowExceptionWhenMissingPartialData(): void
    {
        $this->model->setFrom('john@example.com');
        $this->expectException(PropertyRequiredException::class);
        $this->model->toArray();
    }

    public function testShouldReturnArrayWithData(): void
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

    public function testShouldReturnSameDataFromSerializer(): void
    {
        $this->model->setFrom('john@example.com');
        $this->model->setReplyTo('peter@example.com');
        $this->model->setSenderName('John');

        self::assertSame($this->model->toArray(), $this->model->jsonSerialize());
    }

    public function testShouldSetSettersAndReadGetters(): void
    {
        $this->model->setSenderName('John');
        self::assertSame('John', $this->model->getSenderName());

        $this->model->setReplyTo('peter@example.com');
        self::assertSame('peter@example.com', $this->model->getReplyTo());

        $this->model->setFrom('john@example.com');
        self::assertSame('john@example.com', $this->model->getFrom());
    }
}
