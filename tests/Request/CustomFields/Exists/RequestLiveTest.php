<?php
namespace SmartEmailing\v3\Tests\Request\CustomFields\Exists;

use SmartEmailing\v3\Request\CustomFields\Exists\Request;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;

class RequestLiveTestCase extends BaseTestCase
{
    /**
     * @var Request
     */
    protected $request;

    protected function setUp()
    {
        parent::setUp();
        $this->request = new Request($this->createApi(), '');
    }

    public function testInstance()
    {
        $this->assertInstanceOf(Request::class, $this->request);
    }

    public function testSend()
    {
        // Change this if you want to try live
        $findName = 'Druh produktu';
        $this->request = new Request($this->createApi(), $findName);
        $this->assertEquals($this->request->filter()->name, $findName);
        return; // comment this if you wan to send the request

        // Your
        $customField = $this->request->exists();

        $this->assertTrue(is_object($customField), 'Not found');
    }
}
