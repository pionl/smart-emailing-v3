<?php

namespace SmartEmailing\v3\Tests\Request\Send;

use PHPUnit_Framework_TestCase;
use SmartEmailing\v3\Request\Send\TemplateVariable;

class TemplateVariableTest extends PHPUnit_Framework_TestCase
{
	/** @var TemplateVariable  */
	private $model;

	protected function setUp()
	{
		$this->model = new TemplateVariable();
	}

	public function testShouldReturnSameDataFromSerializer()
	{
		$this->model->setCustomData(['foo' => 'bar']);

		self::assertSame($this->model->toArray(), $this->model->jsonSerialize());
	}

	public function testShouldSetSettersAndReadGetters()
	{
		$this->model->setCustomData(['foo' => 'bar']);
		self::assertSame(['foo' => 'bar'], $this->model->getCustomData());
	}
}
