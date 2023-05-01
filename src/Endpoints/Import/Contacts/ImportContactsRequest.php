<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Import\Contacts;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Endpoints\AbstractRequest;
use SmartEmailing\v3\Endpoints\StatusResponse;
use SmartEmailing\v3\Models\Contact;
use SmartEmailing\v3\Models\Settings;

/**
 * @extends AbstractRequest<StatusResponse>
 */
class ImportContactsRequest extends AbstractRequest
{
    /**
     * The maximum contacts per single request
     *
     * @var int
     */
    public $chunkLimit = 500;

    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @var array
     */
    protected $contacts = [];

    public function __construct(Api $api)
    {
        parent::__construct($api);
        $this->settings = new Settings();
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
     *
     * @param string $email
     *
     * @return Contact
     */
    public function newContact($email)
    {
        $contact = new Contact($email);
        $this->addContact($contact);
        return $contact;
    }

    /**
     * @return Contact[]
     */
    public function contacts()
    {
        return $this->contacts;
    }

    /**
     * @return Settings
     */
    public function settings()
    {
        return $this->settings;
    }

    /**
     * Will send multiple requests because of the 500 count limit
     */
    public function send()
    {
        // There is not enough contacts to enable chunk mode
        if ($this->chunkLimit >= count($this->contacts)) {
            return parent::send();
        }

        return $this->sendInChunkMode();
    }

    //endregion

    //region Data convert
    public function toArray(): array
    {
        return [
            'settings' => $this->settings,
            'data' => $this->contacts,
        ];
    }

    /**
     * Sends contact list in chunk mode
     *
     * @return StatusResponse
     */
    protected function sendInChunkMode()
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

    //region AbstractRequest implementation
    protected function endpoint(): string
    {
        return 'import';
    }

    protected function method(): string
    {
        return 'POST';
    }

    //endregion
}
