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

    public function testFromJSON()
    {
        /** @var ModelMock $model */
        $model = ModelMock::fromJSON([
            'unsupported' => 'test',
            'boolean' => true,
            'null' => null,
            'array' => []
        ]);

        $this->assertObjectNotHasAttribute('unsupported', $model, 'A key should not be imported if not found in default 
        toArray data');
        $this->assertTrue($model->boolean);
        $this->assertNull($model->null);
        $this->assertEquals([], $model->array);
    }

    /**
     * Test that event null values are parsed
     */
    public function testFromJSONParseNull()
    {
        /** @var ModelMock $model */
        $model = ModelMock::fromJSON([
            'array' => null
        ]);
        $this->assertNull($model->array);
    }
}