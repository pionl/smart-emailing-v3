<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests;

use SmartEmailing\v3\Tests\TestCase\BaseTestCase;

class ApiClientTest extends BaseTestCase
{
    /**
     * Tests the client creation
     */
    public function testConstruct(): void
    {
        $this->assertNotNull($this->createApi()->client(), 'The api client must be created');
    }
}
