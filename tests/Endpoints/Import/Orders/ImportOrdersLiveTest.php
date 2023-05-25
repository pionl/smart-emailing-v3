<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\Import\Orders;

use SmartEmailing\v3\Endpoints\AbstractResponse;
use SmartEmailing\v3\Endpoints\Import\Orders\ImportOrdersRequest;
use SmartEmailing\v3\Models\Order;
use SmartEmailing\v3\Tests\TestCase\LiveTestCase;

class ImportOrdersLiveTest extends LiveTestCase
{
    protected ImportOrdersRequest $import;

    protected function setUp(): void
    {
        parent::setUp();

        $this->import = $this->createApi()
            ->importOrdersRequest();
    }

    /**
     * Tests if the endpoint/options is passed to request
     */
    public function testBasic(): void
    {
        $this->assertInstanceOf(ImportOrdersRequest::class, $this->import);
    }

    /**
     * Live test of sync
     */
    public function testContactImport(): void
    {
        // Comment if you want to try
        $this->markTestSkipped();

        $order = new Order('my-eshop', 'ORDER0001', 'jan.novak@smartemailing.cz');

        $this->import->addOrder($order);

        $this->import->settings()
            ->setSkipInvalidOrders(true);

        $response = $this->import->send();

        $this->assertEquals(AbstractResponse::CREATED, $response->status());
        $this->assertEquals(201, $response->statusCode());
    }
}
