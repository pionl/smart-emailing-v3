<?php
namespace SmartEmailing\v3\Request\Import;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Request\AbstractRequest;

class Import extends AbstractRequest implements \JsonSerializable
{
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