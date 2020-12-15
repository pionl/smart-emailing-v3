<?php

namespace SmartEmailing\v3\Tests\Request\Send;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Request\Send\BulkCustomEmails;
use SmartEmailing\v3\Request\Send\Recipient;
use SmartEmailing\v3\Request\Send\Replace;
use SmartEmailing\v3\Request\Send\SenderCredentials;
use SmartEmailing\v3\Request\Send\Task;
use SmartEmailing\v3\Request\Send\TemplateVariable;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class BulkCustomEmailsTest extends ApiStubTestCase
{
	/** @var BulkCustomEmails */
	private $bulkCustomEmails;

	public function testShouldReturnSameDataFromSerializer()
	{
		self::assertSame($this->bulkCustomEmails->toArray(), $this->bulkCustomEmails->jsonSerialize());
	}

	public function testShouldSeeAllKeysInOutputArray()
	{
		$data = $this->bulkCustomEmails->toArray();
		self::assertArrayHasKey('sender_credentials', $data);
		self::assertArrayHasKey('tag', $data);
		self::assertArrayHasKey('email_id', $data);
		self::assertArrayNotHasKey('message_contents', $data);
		self::assertArrayHasKey('tasks', $data);
	}

	public function testShouldSetSettersAndReadGetters()
	{
		$this->bulkCustomEmails->setTag('tag');
		self::assertSame('tag', $this->bulkCustomEmails->getTag());

		$this->bulkCustomEmails->setEmailId(5);
		self::assertSame(5, $this->bulkCustomEmails->getEmailId());

		self::assertSame([], $this->bulkCustomEmails->getTasks());

		$task = new Task();
		$this->bulkCustomEmails->addTask($task);
		self::assertCount(1, $this->bulkCustomEmails->getTasks());
	}

	public function testCompleteValid()
	{
		$credentials = new SenderCredentials();
		$credentials->setFrom('from@example.com');
		$credentials->setReplyTo('to@example.com');
		$credentials->setSenderName('Jean-Luc Picard');

		$recipient = new Recipient();
		$recipient->setEmailAddress('kirk@example.com');

		$replace1 = new Replace();
		$replace1->setKey('key1');
		$replace1->setContent('content1');

		$replace2 = new Replace();
		$replace2->setKey('key2');
		$replace2->setContent('content2');

		$templateVariable = new TemplateVariable();
		$templateVariable->setCustomData(
			[
				'foo' => 'bar',
				'products' => [
					['name' => 'bbb', 'desc' => 'lll'],
					['name' => 'ddd', 'desc' => 'kkk'],
				],
			]
		);

		$task = new Task();
		$task->setRecipient($recipient);
		$task->addReplace($replace1);
		$task->addReplace($replace2);
		$task->setTemplateVariables($templateVariable);

		$this->bulkCustomEmails->setTag('tag_tag');
		$this->bulkCustomEmails->setEmailId(5);
		$this->bulkCustomEmails->setSenderCredentials($credentials);
		$this->bulkCustomEmails->addTask($task);
	}

	protected function setUp()
	{
		$api = new Api('user_name', 'password', '');
		$this->bulkCustomEmails = new BulkCustomEmails($api);
	}
}
