<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\Eshops;

use SmartEmailing\v3\Endpoints\Eshops\EshopOrdersRequest;
use SmartEmailing\v3\Endpoints\Response;
use SmartEmailing\v3\Models\Order;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;

class EshopOrdersLiveTest extends BaseTestCase
{
    protected EshopOrdersRequest $orders;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orders = $this->createApi()
            ->eshopOrders();
    }

    /**
     * Tests if the endpoint/options is passed to request
     */
    public function testBasic(): void
    {
        $this->assertInstanceOf(EshopOrdersRequest::class, $this->orders);
    }

    /**
     * Live test of sync
     */
    public function testContactImport(): void
    {
        // Comment if you want to try
        $this->markTestSkipped();

        $order = new Order('my-eshop', 'ORDER0001', 'jan.novak@smartemailing.cz');

        $this->orders->addOrder($order);

        $response = $this->orders->send();

        $this->assertEquals(Response::SUCCESS, $response->status());
        $this->assertEquals(200, $response->statusCode());
    }
}
