<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Import\Contacts;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Endpoints\Import\AbstractImportRequest;
use SmartEmailing\v3\Models\Contact;
use SmartEmailing\v3\Models\ImportContactsSettings;

class ImportContactsRequest extends AbstractImportRequest
{
    protected ImportContactsSettings $settings;

    public function __construct(Api $api)
    {
        parent::__construct($api);
        $this->settings = new ImportContactsSettings();
    }

    /**
     * @return $this
     */
    public function addContact(Contact $contact)
    {
        $this->data[] = $contact;
        return $this;
    }

    /**
     * Creates new contact and adds to the contact list. Returns the newly created contact
     */
    public function newContact(string $email): Contact
    {
        $contact = new Contact($email);
        $this->addContact($contact);
        return $contact;
    }

    /**
     * @return Contact[]
     */
    public function contacts(): array
    {
        return $this->data;
    }

    public function settings(): ImportContactsSettings
    {
        return $this->settings;
    }

    protected function endpoint(): string
    {
        return 'import';
    }
}
