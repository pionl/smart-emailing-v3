<?php

namespace SmartEmailing\v3\Tests\Request\Send;

use PHPUnit\Framework\TestCase;
use SmartEmailing\v3\Request\Send\Attachment;
use SmartEmailing\v3\Request\Send\Recipient;
use SmartEmailing\v3\Request\Send\Replace;
use SmartEmailing\v3\Request\Send\Task;
use SmartEmailing\v3\Request\Send\TemplateVariable;

class TaskTest extends TestCase
{
	/** @var Task  */
	private $model;

	protected function setUp(): void
	{
		$this->model = new Task();
	}

	public function testShouldReturnSameDataFromSerializer()
	{
		$recipient = new Recipient();
		$recipient->setEmailAddress('kirk@example.com');

		$replace1 = new Replace();
		$replace1->setKey('key1');
		$replace1->setContent('content1');

		$replace2 = new Replace();
		$replace2->setKey('key2');
		$replace2->setContent('content2');

		$templateVariable = new TemplateVariable();
		$templateVariable->setCustomData([
			'foo' => 'bar',
			'products' => [
				['name' => 'bbb', 'desc' => 'lll'],
				['name' => 'ddd', 'desc' => 'kkk']
			]
		]);

		$attachment1 = new Attachment();
		$attachment1->setContentType('image/png');
		$attachment1->setFileName('picture.png');
		$attachment1->setDataBase64('dataaaaaaaaaaaaaaaaaaaaaaaa');

		$attachment2 = new Attachment();
		$attachment2->setContentType('image/gif');
		$attachment2->setFileName('sun.gif');
		$attachment2->setDataBase64('datasunnnnnnnnnnn');


		$this->model->setRecipient($recipient);
		$this->model->addReplace($replace1);
		$this->model->addReplace($replace2);
		$this->model->setTemplateVariables($templateVariable);
		$this->model->addAttachment($attachment1);
		$this->model->addAttachment($attachment2);

		$data = $this->model->toArray();
		self::assertSame($data, $this->model->jsonSerialize());

		self::assertArrayHasKey('recipient', $data);
		self::assertArrayHasKey('replace', $data);
		self::assertArrayHasKey('template_variables', $data);
		self::assertArrayHasKey('attachments', $data);
	}
}
