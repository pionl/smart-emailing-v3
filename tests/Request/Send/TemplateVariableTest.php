<?php

namespace SmartEmailing\v3\Tests\Request\Send;

use PHPUnit\Framework\TestCase;
use SmartEmailing\v3\Request\Send\TemplateVariable;

class TemplateVariableTest extends TestCase
{
	/** @var TemplateVariable  */
	private $model;

	protected function setUp(): void
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
