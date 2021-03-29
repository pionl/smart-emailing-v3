<?php

namespace SmartEmailing\v3\Tests\Request\Send;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Request\Send\BulkCustomSms;
use SmartEmailing\v3\Request\Send\Recipient;
use SmartEmailing\v3\Request\Send\Replace;
use SmartEmailing\v3\Request\Send\Task;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class BulkCustomSmsTest extends ApiStubTestCase
{
	/** @var BulkCustomSms */
	private $bulkCustomSms;

	public function testShouldReturnSameDataFromSerializer()
	{
		self::assertSame($this->bulkCustomSms->toArray(), $this->bulkCustomSms->jsonSerialize());
	}

	public function testShouldSeeAllKeysInOutputArray()
	{
		$data = $this->bulkCustomSms->toArray();
		self::assertArrayHasKey('tag', $data);
		self::assertArrayHasKey('sms_id', $data);
		self::assertArrayNotHasKey('message_contents', $data);
		self::assertArrayHasKey('tasks', $data);
	}

	public function testShouldSetSettersAndReadGetters()
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

	public function testCompleteValid()
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
	}

	public function testRecipientNotValid()
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

	protected function setUp()
	{
		$api = new Api('user_name', 'password', '');
		$this->bulkCustomSms = new BulkCustomSms($api);
	}
}
