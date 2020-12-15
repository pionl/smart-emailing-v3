<?php

namespace SmartEmailing\v3\Tests\Request\Send;

use PHPUnit_Framework_TestCase;
use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Request\Send\SenderCredentials;

class SenderCredentialsTest extends PHPUnit_Framework_TestCase
{
	/** @var SenderCredentials  */
	private $model;

	protected function setUp()
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
