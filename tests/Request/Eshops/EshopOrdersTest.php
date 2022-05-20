<?php
namespace SmartEmailing\v3\Tests\Request\Eshops;

use SmartEmailing\v3\Request\Eshops\EshopOrders;
use SmartEmailing\v3\Request\Eshops\Model\Order;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class EshopOrdersTest extends ApiStubTestCase
{
    /**
     * @var EshopOrders
     */
    protected EshopOrders $orders;

    protected function setUp()
    {
        parent::setUp();

        $this->orders = new EshopOrders($this->apiStub);
    }

    /**
     * Tests if the endpoint/options is passed to request
     */
    public function testEndpoint()
    {
        $this->createEndpointTest($this->orders, 'orders', 'POST', $this->arrayHasKey('json'));
    }

    public function testAddOrder()
    {
        $this->assertCount(1, $this->orders->addOrder(
        	new Order(
        		'my-eshop',
		        'ORDER0001',
		        'jan.novak@smartemailing.cz'
	        ))->orders());
	    $orders = $this->orders->addOrder(
		    new Order(
			    'eshop_name2',
			    'eshop_code2',
			    'jan.novak2@smartemailing.cz'
		    ));
        $this->assertCount(1, $orders->orders());

        $order = $orders->order();
        $this->assertSame('eshop_name2', $order->eshopName);
        $this->assertSame('eshop_code2', $order->eshopCode);
        $this->assertSame('jan.novak2@smartemailing.cz', $order->emailAddress);
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
	/**
	 * Mocks the request and checks if request is returned via send method
	 */
	public function testSend()
	{
		$this->createSendResponse($this->orders, '{
        "status": "ok",
        "meta": [],
        "data": {
            "id": "11eb15523deea6c49042ac1f6bc402ad",
            "created_at": "2020-01-01 00:00:00",
            "contact_id": 2320051,
            "status": "processing",
            "eshop_name": "my-eshop",
            "eshop_code": "ORDER0001",
            "paid_at": null,
            "attributes": [
                {
                    "name": "discount",
                    "value": "Black friday"
                }
            ],
            "items": [
                {
                    "id": "ABC123",
                    "name": "My product",
                    "description": "My product description",
                    "price": {
                        "without_vat": 123.97,
                        "with_vat": 150,
                        "currency": "CZK"
                    },
                    "quantity": 1,
                    "url": "https://www.example.com/my-product",
                    "image_url": "https://www.example.com/images/my-product.jpg",
                    "attributes": [
                        {
                            "name": "manufacturer",
                            "value": "Factory ltd."
                        },
                        {
                            "name": "my other custom attribute",
                            "value": "some value"
                        }
                    ]
                },
                {
                    "id": "XYZ789",
                    "name": "My another product",
                    "description": "My another product description",
                    "price": {
                        "without_vat": 165.7,
                        "with_vat": 200.5,
                        "currency": "CZK"
                    },
                    "quantity": 2,
                    "url": "https://www.example.com/my-another-product",
                    "image_url": "https://www.example.com/images/my-another-product.jpg",
                    "attributes": [
                    {
                            "name": "my other custom attribute2",
                            "value": "some value2"
                        }
                    ]
                }
            ]
        }
}', null);
	}
}
