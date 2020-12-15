<?php

namespace SmartEmailing\v3\Tests\Request\Send;

use PHPUnit\Framework\TestCase;
use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Request\Send\Replace;

class ReplaceTest extends TestCase
{
	/** @var Replace  */
	private $model;

	protected function setUp()
	{
		$this->model = new Replace();
	}

	public function testShouldThrowExceptionWhenMissingAllData()
	{
		$this->expectException(PropertyRequiredException::class);
		$this->model->toArray();
	}

	public function testShouldThrowExceptionWhenMissingPartialData()
	{
		$this->model->setContent('data');
		$this->expectException(PropertyRequiredException::class);
		$this->model->toArray();
	}

	public function testShouldReturnArrayWithData()
	{
		$this->model->setKey('key');
		$this->model->setContent('value');
		$data = $this->model->toArray();

		self::assertSame([
			'key' => 'key',
			'content' => 'value',
		], $data);
	}

	public function testShouldReturnSameDataFromSerializer()
	{
		$this->model->setKey('key');
		$this->model->setContent('value');

		self::assertSame($this->model->toArray(), $this->model->jsonSerialize());
	}

	public function testShouldSetSettersAndReadGetters()
	{
		$this->model->setKey('key');
		self::assertSame('key', $this->model->getKey());

		$this->model->setContent('value');
		self::assertSame('value', $this->model->getContent());
	}
}
