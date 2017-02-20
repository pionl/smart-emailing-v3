<?php
namespace SmartEmailing\v3\Request\Import;

use SmartEmailing\v3\Exceptions\InvalidFormatException;
use function \SmartEmailing\v3\Helpers\convertDate;
use SmartEmailing\v3\Models\Model;
use SmartEmailing\v3\Request\Import\Holder\ContactLists;
use SmartEmailing\v3\Request\Import\Holder\CustomFields;

/**
 * Contact wrapper with public properties (allows force set and easy getter). The fluent setter will help
 * to set values in correct format.
 *
 * @package SmartEmailing\v3\Request\Import
 */
class Contact extends Model
{
    //region Properties
    /**
     * E-mail address of imported contact. This is the only required field.
     * @var string|null required
     */
    public $emailAddress = null;
    /**
     * First name
     * @var string|null
     */
    public $name = null;
    /**
     * @var string|null
     */
    public $surname = null;
    /**
     * Titles before name
     * @var string|null
     */
    public $titlesBefore = null;
    /**
     * Titles after name
     * @var string|null
     */
    public $titlesAfter = null;
    /**
     * @var string|null
     */
    public $salutation = null;
    /**
     * @var string|null
     */
    public $company = null;
    /**
     * @var string|null
     */
    public $street = null;
    /**
     * @var string|null
     */
    public $town = null;
    /**
     * @var string|null
     */
    public $postalCode = null;
    /**
     * @var string|null
     */
    public $country = null;
    /**
     * @var string|null
     */
    public $cellphone = null;
    /**
     * @var string|null
     */
    public $phone = null;
    /**
     * @var string|null
     */
    public $language = null;
    /**
     * Custom notes
     * @var string|null
     */
    public $notes = null;
    /**
     * Allowed values: "M,F,NULL"
     * @var string|null
     */
    public $gender = null;
    /**
     * 0 if Contact is OK, 1 if Contact does not want to receive any of your e-mails anymore. This flag will stop
     * further campaigns. Be careful, setting this value to 1 will also un-subscribe contact from all lists. It is
     * recommended not to send this parameter at all if you do not know what you are doing.
     * @var int
     */
    public $blacklisted = null;
    /**
     * Date of Contact's nameday in YYYY-MM-DD 00:00:00 format
     * @var string|null
     */
    public $nameDay = null;
    /**
     * Date of Contact's birthday in YYYY-MM-DD 00:00:00 format
     * @var string|null
     */
    public $birthday = null;
    /**
     * Contact lists presence of imported contacts. Any contact list presence unlisted in imported data will be
     * untouched. Unsubscribed contacts will stay unsubscribed if settings.preserve_unsubscribed=1
     * @var ContactLists
     */
    protected $contactLists;
    /**
     * Custom fields belonging to contact Custom fields unlisted in imported data will be untouched.
     * @var CustomFields
     */
    protected $customFields;

    //endregion

    /**
     * Contact constructor.
     *
     * @param null|string $emailAddress
     */
    public function __construct($emailAddress)
    {
        $this->emailAddress = $emailAddress;
        $this->customFields = new CustomFields();
        $this->contactLists = new ContactLists();
    }

    //region Setters
    /**
     * @param null|string $emailAddress
     *
     * @return Contact
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    /**
     * @param null|string $name
     *
     * @return Contact
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param null|string $surname
     *
     * @return Contact
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @param null|string $titlesBefore
     *
     * @return Contact
     */
    public function setTitlesBefore($titlesBefore)
    {
        $this->titlesBefore = $titlesBefore;
        return $this;
    }

    /**
     * @param null|string $titlesAfter
     *
     * @return Contact
     */
    public function setTitlesAfter($titlesAfter)
    {
        $this->titlesAfter = $titlesAfter;
        return $this;
    }

    /**
     * @param null|string $salutation
     *
     * @return Contact
     */
    public function setSalutation($salutation)
    {
        $this->salutation = $salutation;
        return $this;
    }

    /**
     * @param null|string $company
     *
     * @return Contact
     */
    public function setCompany($company)
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @param null|string $street
     *
     * @return Contact
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @param null|string $town
     *
     * @return Contact
     */
    public function setTown($town)
    {
        $this->town = $town;
        return $this;
    }

    /**
     * @param null|string $postalCode
     *
     * @return Contact
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * @param null|string $country
     *
     * @return Contact
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @param null|string $cellphone
     *
     * @return Contact
     */
    public function setCellphone($cellphone)
    {
        $this->cellphone = $cellphone;
        return $this;
    }

    /**
     * @param null|string $phone
     *
     * @return Contact
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @param null|string $language
     *
     * @return Contact
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @param null|string $notes
     *
     * @return Contact
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * Allowed values: "M,F,NULL"
     *
     * @param null|string $gender
     *
     * @return Contact
     *
     * @throws InvalidFormatException
     */
    public function setGender($gender)
    {
        InvalidFormatException::checkInArray($gender, [
            'M', 'F', null
        ]);

        $this->gender = $gender;
        return $this;
    }

    /**
     * 0 (false) if Contact is OK, 1 (true) if Contact does not want to receive any of your e-mails anymore. This flag
     * will stop further campaigns. Be careful, setting this value to 1 will also un-subscribe contact from all lists.
     * It is recommended not to send this parameter at all if you do not know what you are doing.
     *
     * @param boolean $blacklisted
     *
     * @return Contact
     */
    public function setBlacklisted($blacklisted = true)
    {
        $this->blacklisted = intval($blacklisted);
        return $this;
    }

    /**
     * Date of Contact's birthday in YYYY-MM-DD 00:00:00 or different format
     *
     * @param null|string $nameDay
     * @param bool        $convertToValidFormat converts the value to valid format
     *
     * @return Contact
     */
    public function setNameDay($nameDay, $convertToValidFormat = true)
    {
        $this->nameDay = convertDate($nameDay, $convertToValidFormat);
        return $this;
    }

    /**
     * Date of Contact's birthday in YYYY-MM-DD 00:00:00 format  or different format
     *
     * @param null|string $birthday
     * @param bool        $convertToValidFormat converts the value to valid format
     *
     * @return Contact
     */
    public function setBirthday($birthday, $convertToValidFormat = true)
    {
        $this->birthday = convertDate($birthday, $convertToValidFormat);
        return $this;
    }

    /**
     * @return ContactLists
     */
    public function contactList()
    {
        return $this->contactLists;
    }

    /**
     * @return CustomFields
     */
    public function customFields()
    {
        return $this->customFields;
    }

    /**
     * Adds an contact list
     *
     * @param ContactList $list
     *
     * @return $this
     */
    public function addContactList(ContactList $list)
    {
        $this->contactLists[] = $list;
        return $this;
    }

    /**
     * Creates a new custom list and stores it to the list
     *
     * @param $id
     *
     * @return ContactList
     *
     * @uses Contact::addContactList()
     */
    public function newContactList($id)
    {
        $list = new ContactList($id);
        $this->addContactList($list);
        return $list;
    }

    //endregion

    /**
     * Converts data to array
     * @return array
     */
    public function toArray()
    {
        return [
            'emailaddress' => $this->emailAddress,
            'name' => $this->name,
            'surname' => $this->surname,
            'titlesbefore' => $this->titlesBefore,
            'titlesafter' => $this->titlesAfter,
            'salution' => $this->salutation,
            'company' => $this->company,
            'street' => $this->street,
            'town' => $this->town,
            'postalcode' => $this->postalCode,
            'country' => $this->country,
            'cellphone' => $this->cellphone,
            'phone' => $this->phone,
            'language' => $this->language,
            'notes' => $this->notes,
            'gender' => $this->gender,
            'blacklisted' => $this->blacklisted,
            'nameday' => $this->nameDay,
            'birthday' => $this->birthday,
            'contactlists' => $this->contactLists,
            'customfields' => $this->customFields
        ];
    }
}