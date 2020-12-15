<?php
namespace SmartEmailing\v3\Tests\Request\Eshops;

use SmartEmailing\v3\Request\Eshops\EshopOrders;
use SmartEmailing\v3\Request\Eshops\Model\Order;
use SmartEmailing\v3\Request\Response;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;

class EshopOrdersLiveTest extends BaseTestCase
{
    /**
     * @var EshopOrders
     */
    protected $orders;


    protected function setUp()
    {
        parent::setUp();
        /** @var  $apiStub */
        $this->orders = $this->createApi()->eshopOrders();
    }

    /**
     * Tests if the endpoint/options is passed to request
     */
    public function testBasic() {
        $this->assertInstanceOf(EshopOrders::class, $this->orders);
    }

    /**
     * Live test of sync
     */
    public function testContactImport()
    {
        // Uncomment if you want to try
        return;

        $order = new Order(
	        'my-eshop',
	        'ORDER0001',
	        'jan.novak@smartemailing.cz'
        );

        $this->orders->addOrder($order);

        $response = $this->orders->send();

        $this->assertEquals(Response::SUCCESS, $response->status());
        $this->assertEquals(200, $response->statusCode());
    }

}
