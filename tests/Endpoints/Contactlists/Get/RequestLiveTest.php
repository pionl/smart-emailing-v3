<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\Contactlist\Get;

use SmartEmailing\v3\Endpoints\Contactlists\Get\ContactlistGetRequest;
use SmartEmailing\v3\Tests\TestCase\LiveTestCase;

class RequestLiveTestCase extends LiveTestCase
{
    protected ContactlistGetRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new ContactlistGetRequest($this->createApi(), 1);
    }

    public function testInstance(): void
    {
        $this->assertInstanceOf(ContactlistGetRequest::class, $this->request);
    }

    public function testSend(): void
    {
        // Change this if you want to try live
        $fieldId = 1;
        $this->request = new ContactlistGetRequest($this->createApi(), $fieldId);

        // Comment if you want to send request
        $this->markTestSkipped();

        // Your
        $contactlist = $this->request->send();

        $this->assertTrue(is_object($contactlist), 'Not found');
    }
}
