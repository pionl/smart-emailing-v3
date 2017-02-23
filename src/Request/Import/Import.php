<?php
namespace SmartEmailing\v3\Request\Import;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Request\AbstractRequest;

class Import extends AbstractRequest implements \JsonSerializable
{
    /**
     * The maximum contacts per single request
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
     * @param Contact $contact
     *
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
     * @return array
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
     * @inheritDoc
     */
    public function send()
    {
        // There is not enough contacts to enable chunk mode
        if ($this->chunkLimit >= count($this->contacts)) {
            return parent::send();
        }

        return $this->sendInChunkMode();
    }

    /**
     * Sends contact list in chunk mode
     * @return \SmartEmailing\v3\Request\Response
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
    protected function endpoint()
    {
        return 'import';
    }

    protected function options()
    {
        return [
            'json' => $this->jsonSerialize()
        ];
    }

    protected function method()
    {
        return 'POST';
    }

    //endregion

    //region Data convert
    /**
     * Converts data to array
     * @return array
     */
    public function toArray()
    {
        return [
            'settings' => $this->settings,
            'data' => $this->contacts
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
    //endregion

}