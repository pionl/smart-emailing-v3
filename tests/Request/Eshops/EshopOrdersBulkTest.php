<?php
namespace SmartEmailing\v3\Tests\Request\Eshops;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use SmartEmailing\v3\Exceptions\RequestException;
use SmartEmailing\v3\Request\Eshops\EshopOrdersBulk;
use SmartEmailing\v3\Request\Eshops\Model\Order;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class EshopOrdersBulkTest extends ApiStubTestCase
{
    /**
     * @var EshopOrdersBulk
     */
    protected EshopOrdersBulk $orders;

    protected function setUp()
    {
        parent::setUp();

        $this->orders = new EshopOrdersBulk($this->apiStub);
    }

    /**
     * Tests if the endpoint/options is passed to request
     */
    public function testEndpoint()
    {
        $this->createEndpointTest($this->orders, 'orders-bulk', 'POST', $this->arrayHasKey('json'));
    }

    public function testAddOrder()
    {
        $this->assertCount(1, $this->orders->addOrder(
        	new Order(
        		'my-eshop',
		        'ORDER0001',
		        'jan.novak@smartemailing.cz'
	        ))->orders());
        $this->assertCount(2, $this->orders->addOrder(
        	new Order(
        		'my-eshop2',
		        'ORDER00012',
		        'jan.novak2@smartemailing.cz'
	        ))->orders());
        $this->assertCount(3, $this->orders->addOrder(
        	new Order(
        		'my-eshop2',
		        'ORDER00013',
		        'jan.novak3@smartemailing.cz'
	        ))->orders());
    }

    public function testNewOrder()
    {
        $this->orders->newOrder(
        	'my-eshop',
	        'ORDER0001',
	        'jan.novak@smartemailing.cz'
        );
	    $this->assertCount(1, $this->orders->orders());
    }

	public function testChunkMode()
	{
		// Build a contact list 2,5 larger then chunk limit
		for ($i = 1; $i <= 1250; $i++) {
			$this->orders->addOrder(
				new Order(
					'my-eshop',
					"ORDER000{$i}",
					"jan.novak+{$i}@test.cz"
				)
			);
		}

		// Build the client that will mock the client->request method
		$client = $this->createMock(Client::class);
		$response = $this->createMock(ResponseInterface::class);

		// The array will be chunked in 3 groups
		$willBeCalled = $this->exactly(3);

		// Make a response that is valid and ok - prevent exception
		$response->expects($this->atLeastOnce())->method('getBody')->willReturn($this->defaultReturnResponse);
		$called = 0;
		$client->expects($willBeCalled)->method('request')->with(
			$this->valueConstraint('POST'),
			$this->valueConstraint('orders-bulk'),
			$this->callback(function ($value) use (&$called) {
				$this->assertTrue(is_array($value), 'Options should be array');
				$this->assertArrayHasKey('json', $value, 'Options should contain json');
				$called++;

				switch ($called) {
					case 1:
						$this->assertCount(500, $value['json']);
						$this->assertEquals('jan.novak+1@test.cz', $value['json'][0]->emailAddress);
						break;
					case 2:
						$this->assertCount(500, $value['json']);
						$this->assertEquals('jan.novak+501@test.cz', $value['json'][0]->emailAddress);
						break;
					case 3: // Last pack of contacts is smaller
						$this->assertCount(250, $value['json']);
						$this->assertEquals('jan.novak+1001@test.cz', $value['json'][0]->emailAddress);
						break;
				}

				return true;
			})
		)->willReturn($response);

		$this->apiStub->method('client')->willReturn($client);
		$this->orders->send();
	}

	public function testChunkModeError()
	{
		// Build a contact list 2,5 larger then chunk limit
		for ($i = 1; $i <= 1250; $i++) {
			$this->orders->addOrder(
				new Order(
					'my-eshop',
					"ORDER000{$i}",
					"jan.novak+{$i}@test.cz"
				)
			);
		}

		// Build the client that will mock the client->request method
		$client = $this->createMock(Client::class);
		$response = $this->createMock(ResponseInterface::class);

		// Make a response that is valid and ok - prevent exception
		$response->expects($this->atLeastOnce())
			->method('getBody')
			->willReturn('{
            "status": "error",
            "meta": [],
            "message": "Emailaddress invalid@email@gmail.com is not valid email address."
        }');
		$response->expects($this->once())->method('getStatusCode')->willReturn(422);

		$client->expects($this->once())->method('request')->with(
			$this->valueConstraint('POST'),
			$this->valueConstraint('orders-bulk'),
			$this->callback(function ($value) {
				$this->assertTrue(is_array($value), 'Options should be array');
				$this->assertArrayHasKey('json', $value, 'JSON must have data array');
				$this->assertCount(500, $value['json']);
				$this->assertEquals('jan.novak+1@test.cz', $value['json'][0]->emailAddress);

				return true;
			})
		)->willReturn($response);

		$this->apiStub->method('client')->willReturn($client);
		$this->expectException(RequestException::class);
		$this->orders->send();
	}
}
