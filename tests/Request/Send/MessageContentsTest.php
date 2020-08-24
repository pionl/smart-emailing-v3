<?php

namespace SmartEmailing\v3\Tests\Request\Send;

use PHPUnit_Framework_TestCase;
use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Request\Send\MessageContents;

class MessageContentsTest extends PHPUnit_Framework_TestCase
{
	/** @var MessageContents  */
	private $model;

	protected function setUp()
	{
		$this->model = new MessageContents();
	}

	public function testShouldThrowExceptionWhenMissingAllData()
	{
		$this->expectException(PropertyRequiredException::class);
		$this->model->toArray();
	}

	public function testShouldThrowExceptionWhenMissingPartialData()
	{
		$this->model->setSubject('Email subject');
		$this->expectException(PropertyRequiredException::class);
		$this->model->toArray();
	}


	public function testShouldReturnArrayWithData()
	{
		$this->model->setSubject('Email_subject');
		$this->model->setHtmlBody('HTMLBODY');
		$this->model->setTextBody('TEXTBODY');
		$data = $this->model->toArray();

		self::assertSame([
			'subject' => 'Email_subject',
			'html_body' => 'HTMLBODY',
			'text_body' => 'TEXTBODY',
		], $data);
	}

	public function testShouldReturnSameDataFromSerializer()
	{
		$this->model->setSubject('Email_subject');
		$this->model->setHtmlBody('HTMLBODY');
		$this->model->setTextBody('TEXTBODY');

		self::assertSame($this->model->toArray(), $this->model->jsonSerialize());
	}

	public function testShouldSetSettersAndReadGetters()
	{
		$this->model->setSubject('Email_subject');
		self::assertSame('Email_subject', $this->model->getSubject());

		$this->model->setHtmlBody('HTMLBODY');
		self::assertSame('HTMLBODY', $this->model->getHtmlBody());

		$this->model->setTextBody('TEXTBODY');
		self::assertSame('TEXTBODY', $this->model->getTextBody());
	}
}
