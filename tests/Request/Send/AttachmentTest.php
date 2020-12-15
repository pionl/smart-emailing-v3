<?php

namespace SmartEmailing\v3\Tests\Request\Send;

use PHPUnit_Framework_TestCase;
use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Request\Send\Attachment;

class AttachmentTest extends PHPUnit_Framework_TestCase
{
	/** @var Attachment  */
	private $model;

	protected function setUp()
	{
		$this->model = new Attachment();
	}

	public function testShouldThrowExceptionWhenMissingAllData()
	{
		$this->expectException(PropertyRequiredException::class);
		$this->model->toArray();
	}

	public function testShouldThrowExceptionWhenMissingPartialData()
	{
		$this->model->setFileName('car.png');
		$this->expectException(PropertyRequiredException::class);
		$this->model->toArray();
	}

	public function testShouldReturnArrayWithData()
	{
		$this->model->setFileName('car.png');
		$this->model->setContentType('image/png');
		$this->model->setDataBase64('data');
		$data = $this->model->toArray();

		self::assertSame([
			'file_name' => 'car.png',
			'content_type' => 'image/png',
			'data_base64' => 'data',
		], $data);
	}

	public function testShouldReturnSameDataFromSerializer()
	{
		$this->model->setFileName('car.png');
		$this->model->setContentType('image/png');
		$this->model->setDataBase64('data');

		self::assertSame($this->model->toArray(), $this->model->jsonSerialize());
	}

	public function testShouldSetSettersAndReadGetters()
	{
		$this->model->setFileName('car.png');
		self::assertSame('car.png', $this->model->getFileName());

		$this->model->setContentType('image/png');
		self::assertSame('image/png', $this->model->getContentType());

		$this->model->setDataBase64('data');
		self::assertSame('data', $this->model->getDataBase64());
	}
}
