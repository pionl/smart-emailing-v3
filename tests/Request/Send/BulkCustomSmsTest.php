<?php

namespace SmartEmailing\v3\Tests\Request\Send;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Exceptions\RequestException;
use SmartEmailing\v3\Request\Send\BulkCustomSms;
use SmartEmailing\v3\Request\Send\Recipient;
use SmartEmailing\v3\Request\Send\Replace;
use SmartEmailing\v3\Request\Send\Task;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class BulkCustomSmsTest extends ApiStubTestCase
{
	/** @var BulkCustomSms */
	protected $bulkCustomSms;

	protected function setUp()
	{
		parent::setUp();

		$this->bulkCustomSms = new BulkCustomSms($this->apiStub);
	}

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

	public function testChunkMode()
	{
		// Build a contact list 2,5 larger then chunk limit
		for ($i = 1; $i <= 1250; $i++) {
			$recipient = new Recipient();
			$recipient->setEmailAddress("kirk+{$i}@example.com");
			$recipient->setCellphone('+420777888777');
			$task = new Task();
			$task->setRecipient($recipient);
			$this->bulkCustomSms->addTask($task);
		}

		// Build the client that will mock the client->request method
		$client = $this->createMock(Client::class);
		$response = $this->createMock(ResponseInterface::class);

		// The array will be chunked in 3 groups
		$willBeCalled = $this->exactly(3);

		// Make a response that is valid and ok - prevent exception
		$response->expects($this->atLeastOnce())->method('getBody')->willReturn($this->defaultReturnResponse);
		$called = 0;
		$client->expects($willBeCalled)->method('request')->with(
			$this->valueConstraint('POST'),
			$this->valueConstraint('send/custom-sms-bulk'),
			$this->callback(function ($value) use (&$called) {
				$this->assertTrue(is_array($value), 'Options should be array');
				$this->assertArrayHasKey('json', $value, 'Options should contain json');
				$this->assertArrayHasKey('tasks', $value['json'], 'JSON must have data array');
				$called++;

				switch ($called) {
					case 1:
						$this->assertCount(500, $value['json']['tasks']);
						$this->assertEquals('kirk+1@example.com', $value['json']['tasks'][0]->getRecipient()->getEmailAddress());
						break;
					case 2:
						$this->assertCount(500, $value['json']['tasks']);
						$this->assertEquals('kirk+501@example.com', $value['json']['tasks'][0]->getRecipient()->getEmailAddress());
						break;
					case 3: // Last pack of contacts is smaller
						$this->assertCount(250, $value['json']['tasks']);
						$this->assertEquals('kirk+1001@example.com', $value['json']['tasks'][0]->getRecipient()->getEmailAddress());
						break;
				}

				return true;
			})
		)->willReturn($response);

		$this->apiStub->method('client')->willReturn($client);
		$this->bulkCustomSms->send();
	}

	public function testChunkModeError()
	{
		// Build a contact list 2,5 larger then chunk limit
		for ($i = 1; $i <= 1250; $i++) {
			$recipient = new Recipient();
			$recipient->setEmailAddress("kirk+{$i}@example.com");
			$recipient->setCellphone('+420777888777');
			$task = new Task();
			$task->setRecipient($recipient);
			$this->bulkCustomSms->addTask($task);
		}

		// Build the client that will mock the client->request method
		$client = $this->createMock(Client::class);
		$response = $this->createMock(ResponseInterface::class);

		// Make a response that is valid and ok - prevent exception
		$response->expects($this->atLeastOnce())
			->method('getBody')
			->willReturn('{
            "status": "error",
            "meta": [],
            "message": "Problem at key tasks: Problem at key recipient: Problem at key emailaddress: Invalid emailaddress: invalid@email@gmail.com"
        }');
		$response->expects($this->once())->method('getStatusCode')->willReturn(422);

		$client->expects($this->once())->method('request')->with(
			$this->valueConstraint('POST'),
			$this->valueConstraint('send/custom-sms-bulk'),
			$this->callback(function ($value) {
				$this->assertTrue(is_array($value), 'Options should be array');
				$this->assertArrayHasKey('json', $value, 'Options should contain json');
				$this->assertArrayHasKey('tasks', $value['json'], 'JSON must have data array');
				$this->assertCount(500, $value['json']['tasks']);
				$this->assertEquals('kirk+1@example.com', $value['json']['tasks'][0]->getRecipient()->getEmailAddress());

				return true;
			})
		)->willReturn($response);

		$this->apiStub->method('client')->willReturn($client);
		$this->expectException(RequestException::class);
		$this->bulkCustomSms->send();
	}
}
