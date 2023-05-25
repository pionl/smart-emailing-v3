<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\Import\Orders;

use SmartEmailing\v3\Endpoints\Import\Orders\ImportOrdersRequest;
use SmartEmailing\v3\Exceptions\RequestException;
use SmartEmailing\v3\Models\ImportOrdersSettings;
use SmartEmailing\v3\Models\Order;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class ImportOrdersTestCase extends ApiStubTestCase
{
    protected ImportOrdersRequest $import;

    protected function setUp(): void
    {
        parent::setUp();

        $this->import = new ImportOrdersRequest($this->apiStub);
    }

    /**
     * Tests if the endpoint/options is passed to request
     */
    public function testEndpoint(): void
    {
        $this->expectClientRequest('import-orders', 'POST', $this->arrayHasKey('json'));
        $this->import->send();
    }

    public function testConstruct(): void
    {
        $this->assertInstanceOf(ImportOrdersSettings::class, $this->import->settings());
        $this->assertCount(0, $this->import->orders());
    }

    public function testAddContact(): void
    {
        $this->assertCount(
            1,
            $this->import->addOrder(new Order('my-eshop', 'ORDER0001', 'jan.novak@smartemailing.cz'))
                ->orders()
        );
        $this->assertCount(
            2,
            $this->import->addOrder(new Order('my-eshop', 'ORDER0002', 'jan.novak2@smartemailing.cz'))
                ->orders()
        );
        $this->assertCount(
            3,
            $this->import->addOrder(new Order('my-eshop', 'ORDER0003', 'jan.novak3@smartemailing.cz'))
                ->orders()
        );
    }

    public function testNewContact(): void
    {
        $this->import->newOrder('my-eshop', 'ORDER0001', 'jan.novak@smartemailing.cz');
        $this->assertCount(1, $this->import->orders());
    }

    public function testChunkMode(): void
    {
        // Build a contact list 2,5 larger then chunk limit
        for ($i = 1; $i <= 1250; ++$i) {
            $this->import->addOrder(new Order('my-eshop', 'ORDER' . $i, sprintf('test+%d@test.cz', $i)));
        }

        $response = $this->createClientResponse();

        $this->expectClientRequest('import-orders', 'POST', $this->callback(function ($value): bool {
            $this->assertHasJsonData($value, 'settings');
            $data = $this->assertHasJsonData($value, 'data');
            $this->assertCount(500, $data);
            $this->assertEquals('test+1@test.cz', $data[0]['emailaddress']);
            return true;
        }), $response);

        $this->expectClientRequest('import-orders', 'POST', $this->callback(function ($value): bool {
            $this->assertHasJsonData($value, 'settings');
            $data = $this->assertHasJsonData($value, 'data');
            $this->assertCount(500, $data);
            $this->assertEquals('test+501@test.cz', $data[0]['emailaddress']);
            return true;
        }), $response);

        $this->expectClientRequest('import-orders', 'POST', $this->callback(function ($value): bool {
            $this->assertHasJsonData($value, 'settings');
            $data = $this->assertHasJsonData($value, 'data');
            $this->assertCount(250, $data);
            $this->assertEquals('test+1001@test.cz', $data[0]['emailaddress']);
            return true;
        }), $response);

        $this->import->send();
    }

    public function testChunkModeError(): void
    {
        // Build a contact list 2,5 larger then chunk limit
        for ($i = 1; $i <= 1250; ++$i) {
            $this->import->addOrder(new Order('my-eshop', 'ORDER' . $i, sprintf('test+%d@test.cz', $i)));
        }

        $response = $this->createClientErrorResponse(
            'Emailaddress invalid@email@gmail.com is not valid email address.'
        );

        $this->expectClientRequest('import-orders', 'POST', $this->callback(function ($value): bool {
            $this->assertHasJsonData($value, 'settings');
            $data = $this->assertHasJsonData($value, 'data');
            $this->assertCount(500, $data);
            $this->assertEquals('test+1@test.cz', $data[0]['emailaddress']);
            return true;
        }), $response);

        $this->expectException(RequestException::class);
        $this->import->send();
    }
}
