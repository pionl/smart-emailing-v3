<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Import\Contacts;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Endpoints\AbstractRequest;
use SmartEmailing\v3\Endpoints\StatusResponse;
use SmartEmailing\v3\Models\Contact;
use SmartEmailing\v3\Models\ImportContactsSettings;

/**
 * @extends AbstractRequest<StatusResponse>
 */
class ImportContactsRequest extends AbstractRequest
{
    /**
     * The maximum contacts per single request
     *
     * @var int<1, 500>
     */
    public int $chunkLimit = 500;

    protected ImportContactsSettings $settings;

    protected array $contacts = [];

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
        $this->contacts[] = $contact;
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
        return $this->contacts;
    }

    public function settings(): ImportContactsSettings
    {
        return $this->settings;
    }

    /**
     * Will send multiple requests because of the 500 count limit
     */
    public function send(): StatusResponse
    {
        // There is not enough contacts to enable chunk mode
        if ($this->chunkLimit >= count($this->contacts)) {
            return parent::send();
        }

        $response = $this->sendInChunkMode();
        if ($response instanceof StatusResponse === false) {
            throw new \Exception('Response is null');
        }
        return $response;
    }

    /**
     * @return array{settings: ImportContactsSettings, data: mixed[]}
     */
    public function toArray(): array
    {
        return [
            'settings' => $this->settings,
            'data' => $this->contacts,
        ];
    }

    /**
     * Sends contact list in chunk mode
     */
    protected function sendInChunkMode(): ?StatusResponse
    {
        // Store the original contact list
        $originalFullContactList = $this->contacts;
        $lastResponse = null;

        // Chunk the array of contacts send it in multiple requests
        foreach (array_chunk($this->contacts, $this->chunkLimit) as $contacts) {
            // Store the contacts that will be passed
            $this->contacts = $contacts;

            $lastResponse = parent::send();
        }

        // Restore to original array
        $this->contacts = $originalFullContactList;

        return $lastResponse;
    }

    protected function endpoint(): string
    {
        return 'import';
    }

    protected function method(): string
    {
        return 'POST';
    }
}
