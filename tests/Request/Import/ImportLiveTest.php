<?php
namespace SmartEmailing\v3\Tests\Request\Import;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Request\Import\Contact;
use SmartEmailing\v3\Request\Import\Import;
use SmartEmailing\v3\Request\Import\Settings;
use SmartEmailing\v3\Request\Response;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;

class ImportLiveTest extends BaseTestCase
{
    /**
     * @var Import
     */
    protected $import;


    protected function setUp()
    {
        parent::setUp();
        /** @var  $apiStub */
        $this->import = $this->createApi()->import();
    }

    /**
     * Tests if the endpoint/options is passed to request
     */
    public function testBasic() {
        $this->assertInstanceOf(Import::class, $this->import);
    }

    /**
     * Live test of sync
     */
    public function testContactImport()
    {
        // Uncomment if you want to try
        return;

        $contactFull = new Contact('test2@test.cz');

        $contactFull->setGender('M')->setNotes('test')->setBirthday('today')->setBlacklisted(true);

        $this->import->addContact(new Contact('test@test.cz'))->addContact($contactFull);

        $this->import->settings()->setSkipInvalidEmails(true);

        $response = $this->import->send();

        $this->assertEquals(Response::CREATED, $response->status());
        $this->assertEquals(201, $response->statusCode());
    }

}