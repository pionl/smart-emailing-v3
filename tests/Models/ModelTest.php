<?php
namespace SmartEmailing\v3\Tests\Models;

use SmartEmailing\v3\Models\Model;
use SmartEmailing\v3\Tests\Mock\ModelMock;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;

class ModelTest extends BaseTestCase
{
    /**
     * @var Model
     */
    protected $model;

    protected function setUp()
    {
        parent::setUp();
        $this->model = new ModelMock();
    }

    public function testJsonSerializeFilterCount()
    {
        $this->assertCount(4, $this->model->jsonSerialize());
    }

    public function testJsonSerializeEquals()
    {
        $array = $this->model->jsonSerialize();
        $this->assertArrayHasKey('boolean', $array);
        $this->assertArrayHasKey('string', $array);
        $this->assertArrayHasKey('array', $array);
        $this->assertArrayHasKey('holder', $array);
    }
}