<?php
namespace SmartEmailing\v3\Tests\Request\Import;

use SmartEmailing\v3\Request\Import\Contact;
use SmartEmailing\v3\Request\Import\Import;
use SmartEmailing\v3\Request\Import\Settings;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class ImportTestCase extends ApiStubTestCase
{
    /**
     * @var Import
     */
    protected $import;

    protected function setUp()
    {
        parent::setUp();

        $this->import = new Import($this->apiStub);
    }

    /**
     * Tests if the endpoint/options is passed to request
     */
    public function testEndpoint() {
        $this->createEndpointTest($this->import, 'import', 'POST', $this->arrayHasKey('json'));
    }

    public function testConstruct()
    {
        $this->assertInstanceOf(Settings::class, $this->import->settings());
        $this->assertCount(0, $this->import->contacts());
    }

    public function testAddContact()
    {
        $this->assertCount(1, $this->import->addContact(new Contact('test@test.cz'))->contacts());
        $this->assertCount(2, $this->import->addContact(new Contact('test2@test.cz'))->contacts());
        $this->assertCount(3, $this->import->addContact(new Contact('test@test.cz'))->contacts());
    }

    public function testNewContact()
    {
        $this->import->newContact('test@test.cz');
        $this->assertCount(1, $this->import->contacts());
    }

}