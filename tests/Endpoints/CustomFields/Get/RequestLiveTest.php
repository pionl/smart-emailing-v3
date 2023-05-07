<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\CustomFields\Get;

use SmartEmailing\v3\Endpoints\CustomFields\Get\CustomFieldsGetRequest;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;

class RequestLiveTestCase extends BaseTestCase
{
    protected CustomfieldsGetRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new CustomfieldsGetRequest($this->createApi(), 1);
    }

    public function testInstance(): void
    {
        $this->assertInstanceOf(CustomfieldsGetRequest::class, $this->request);
    }

    public function testSend(): void
    {
        // Change this if you want to try live
        $fieldId = 1;
        $this->request = new CustomfieldsGetRequest($this->createApi(), $fieldId);

        // Comment if you want to send request
        $this->markTestSkipped();

        // Your
        $customField = $this->request->send();

        $this->assertTrue(is_object($customField), 'Not found');
    }
}
