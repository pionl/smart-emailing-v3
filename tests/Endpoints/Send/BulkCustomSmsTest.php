<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\Send;

use SmartEmailing\v3\Endpoints\Send\BulkCustomSms\BulkCustomSmsRequest;
use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Exceptions\RequestException;
use SmartEmailing\v3\Models\Recipient;
use SmartEmailing\v3\Models\Replace;
use SmartEmailing\v3\Models\Task;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class BulkCustomSmsTest extends ApiStubTestCase
{
    protected BulkCustomSmsRequest $bulkCustomSms;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bulkCustomSms = new BulkCustomSmsRequest($this->apiStub);
    }

    public function testShouldReturnSameDataFromSerializer(): void
    {
        self::assertSame($this->bulkCustomSms->toArray(), $this->bulkCustomSms->jsonSerialize());
    }

    public function testShouldSeeAllKeysInOutputArray(): void
    {
        $data = $this->bulkCustomSms->toArray();
        self::assertArrayHasKey('tag', $data);
        self::assertArrayHasKey('sms_id', $data);
        self::assertArrayNotHasKey('message_contents', $data);
        self::assertArrayHasKey('tasks', $data);
    }

    public function testShouldSetSettersAndReadGetters(): void
    {
        $this->bulkCustomSms->setTag('tag');
        self::assertSame('tag', $this->bulkCustomSms->getTag());

        $this->bulkCustomSms->setSmsId(5);
        self::assertSame(5, $this->bulkCustomSms->getSmsId());

        self::assertSame([], $this->bulkCustomSms->getTasks());

        $task = new Task();
        $this->bulkCustomSms->addTask($task);
        self::assertCount(1, $this->bulkCustomSms->getTasks());
    }

    public function testCompleteValid(): void
    {
        $recipient = new Recipient();
        $recipient->setEmailAddress('kirk@example.com');
        $recipient->setCellphone('+420777888777');

        $replace1 = new Replace();
        $replace1->setKey('key1');
        $replace1->setContent('content1');

        $replace2 = new Replace();
        $replace2->setKey('key2');
        $replace2->setContent('content2');

        $task = new Task();
        $task->setRecipient($recipient);
        $task->addReplace($replace1);
        $task->addReplace($replace2);

        $this->bulkCustomSms->setTag('tag_tag');
        $this->bulkCustomSms->setSmsId(5);
        $this->bulkCustomSms->addTask($task);

        $this->assertIsArray($this->bulkCustomSms->jsonSerialize());
    }

    public function testRecipientNotValid(): void
    {
        $this->expectExceptionCode(500);
        $this->expectException(PropertyRequiredException::class);
        $this->expectExceptionMessage('You must set cellphone for recipient - missing cellphone');

        $recipient = new Recipient();
        $recipient->setEmailAddress('kirk@example.com');

        $task = new Task();
        $task->setRecipient($recipient);

        $this->bulkCustomSms->setTag('tag_tag');
        $this->bulkCustomSms->setSmsId(5);
        $this->bulkCustomSms->addTask($task);
        $this->bulkCustomSms->toArray();
    }

    public function testChunkMode(): void
    {
        // Build a contact list 2,5 larger then chunk limit
        for ($i = 1; $i <= 1250; ++$i) {
            $recipient = new Recipient();
            $recipient->setEmailAddress(sprintf('kirk+%d@example.com', $i));
            $recipient->setCellphone('+420777888777');
            $task = new Task();
            $task->setRecipient($recipient);
            $this->bulkCustomSms->addTask($task);
        }

        $response = $this->createClientResponse();

        $this->expectClientRequest('send/custom-sms-bulk', 'POST', $this->callback(function ($value): bool {
            $data = $this->assertHasJsonData($value, 'tasks');
            $this->assertCount(500, $data);
            $this->assertEquals('kirk+1@example.com', $data[0]['recipient']['emailaddress']);
            return true;
        }), $response);

        $this->expectClientRequest('send/custom-sms-bulk', 'POST', $this->callback(function ($value): bool {
            $data = $this->assertHasJsonData($value, 'tasks');
            $this->assertCount(500, $data);
            $this->assertEquals('kirk+501@example.com', $data[0]['recipient']['emailaddress']);
            return true;
        }), $response);

        $this->expectClientRequest('send/custom-sms-bulk', 'POST', $this->callback(function ($value): bool {
            $data = $this->assertHasJsonData($value, 'tasks');
            $this->assertCount(250, $data);
            $this->assertEquals('kirk+1001@example.com', $data[0]['recipient']['emailaddress']);
            return true;
        }), $response);

        $this->bulkCustomSms->send();
    }

    public function testChunkModeError(): void
    {
        // Build a contact list 2,5 larger then chunk limit
        for ($i = 1; $i <= 1250; ++$i) {
            $recipient = new Recipient();
            $recipient->setEmailAddress(sprintf('kirk+%d@example.com', $i));
            $recipient->setCellphone('+420777888777');
            $task = new Task();
            $task->setRecipient($recipient);
            $this->bulkCustomSms->addTask($task);
        }

        $response = $this->createClientErrorResponse(
            'Problem at key tasks: Problem at key recipient: Problem at key emailaddress: Invalid emailaddress: invalid@email@gmail.com'
        );

        $this->expectClientRequest('send/custom-sms-bulk', 'POST', $this->callback(function ($value): bool {
            $data = $this->assertHasJsonData($value, 'tasks');
            $this->assertCount(500, $data);
            $this->assertEquals('kirk+1@example.com', $data[0]['recipient']['emailaddress']);
            return true;
        }), $response);

        $this->expectException(RequestException::class);
        $this->bulkCustomSms->send();
    }
}
