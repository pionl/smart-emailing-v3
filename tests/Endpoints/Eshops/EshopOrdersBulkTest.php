<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\Eshops;

use SmartEmailing\v3\Endpoints\Eshops\EshopOrdersBulkRequest;
use SmartEmailing\v3\Exceptions\RequestException;
use SmartEmailing\v3\Models\OrderWithFeedItems;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class EshopOrdersBulkTest extends ApiStubTestCase
{
    protected EshopOrdersBulkRequest $orders;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orders = new EshopOrdersBulkRequest($this->apiStub);
    }

    /**
     * Tests if the endpoint/options is passed to request
     */
    public function testEndpoint(): void
    {
        $this->expectClientRequest('orders-bulk', 'POST', $this->arrayHasKey('json'));
        $this->orders->send();
    }

    public function testAddOrder(): void
    {
        $this->assertCount(1, $this->orders->addOrder(
            new OrderWithFeedItems('my-eshop', 'ORDER0001', 'jan.novak@smartemailing.cz')
        )->orders());
        $this->assertCount(2, $this->orders->addOrder(
            new OrderWithFeedItems('my-eshop2', 'ORDER00012', 'jan.novak2@smartemailing.cz')
        )->orders());
        $this->assertCount(3, $this->orders->addOrder(
            new OrderWithFeedItems('my-eshop2', 'ORDER00013', 'jan.novak3@smartemailing.cz')
        )->orders());
    }

    public function testNewOrder(): void
    {
        $this->orders->newOrder('my-eshop', 'ORDER0001', 'jan.novak@smartemailing.cz');
        $this->assertCount(1, $this->orders->orders());
    }

    public function testChunkMode(): void
    {
        // Build a contact list 2,5 larger then chunk limit
        for ($i = 1; $i <= 1250; ++$i) {
            $this->orders->addOrder(
                new OrderWithFeedItems('my-eshop', sprintf('ORDER000%d', $i), sprintf('jan.novak+%d@test.cz', $i))
            );
        }

        $response = $this->createClientResponse();

        $this->expectClientRequest('orders-bulk', 'POST', $this->callback(function ($value): bool {
            $this->assertArrayHasKey('json', $value, 'Options should contain json');
            $this->assertCount(500, $value['json']);
            $this->assertEquals('jan.novak+1@test.cz', $value['json'][0]['emailaddress']);
            return true;
        }), $response);

        $this->expectClientRequest('orders-bulk', 'POST', $this->callback(function ($value): bool {
            $this->assertArrayHasKey('json', $value, 'Options should contain json');
            $this->assertCount(500, $value['json']);
            $this->assertEquals('jan.novak+501@test.cz', $value['json'][0]['emailaddress']);
            return true;
        }), $response);

        $this->expectClientRequest('orders-bulk', 'POST', $this->callback(function ($value): bool {
            $this->assertArrayHasKey('json', $value, 'Options should contain json');
            $this->assertCount(250, $value['json']);
            $this->assertEquals('jan.novak+1001@test.cz', $value['json'][0]['emailaddress']);
            return true;
        }), $response);

        $this->orders->send();
    }

    public function testChunkModeError(): void
    {
        // Build a contact list 2,5 larger then chunk limit
        for ($i = 1; $i <= 1250; ++$i) {
            $this->orders->addOrder(
                new OrderWithFeedItems('my-eshop', sprintf('ORDER000%d', $i), sprintf('jan.novak+%d@test.cz', $i))
            );
        }

        $response = $this->createClientErrorResponse(
            'Emailaddress invalid@email@gmail.com is not valid email address.'
        );

        $this->expectClientRequest('orders-bulk', 'POST', $this->callback(function ($value): bool {
            $this->assertArrayHasKey('json', $value, 'JSON must have data array');
            $this->assertCount(500, $value['json']);
            $this->assertEquals('jan.novak+1@test.cz', $value['json'][0]['emailaddress']);
            return true;
        }), $response);

        $this->expectException(RequestException::class);
        $this->orders->send();
    }
}
