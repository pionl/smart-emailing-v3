<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Models\Holder\ContactLists;
use SmartEmailing\v3\Models\Holder\CustomFieldValues;
use SmartEmailing\v3\Models\Holder\Purposes;

/**
 * Contact wrapper with public properties (allows force set and easy getter). The fluent setter will help to set values
 * in correct format.
 */
class Contact extends Model
{
    public const SELECT_FIELDS = [
        'id',
        'language',
        'blacklisted',
        'emailaddress',
        'name',
        'surname',
        'titlesbefore',
        'titlesafter',
        'birthday',
        'nameday',
        'salution',
        'gender',
        'company',
        'street',
        'town',
        'country',
        'postalcode',
        'notes',
        'phone',
        'cellphone',
        'realname',
    ];
    public const SORT_FIELDS = self::SELECT_FIELDS;
    public const EXPAND_FIELDS = ['customfields'];
    public const MALE = 'M';
    public const FEMALE = 'F';

    public ?int $id = null;

    /**
     * E-mail address of imported contact. This is the only required field.
     *
     * @var string|null required
     */
    public ?string $emailAddress = null;

    /**
     * First name
     */
    public ?string $name = null;

    public ?string $surname = null;

    /**
     * Titles before name
     */
    public ?string $titlesBefore = null;

    /**
     * Titles after name
     */
    public ?string $titlesAfter = null;

    public ?string $salutation = null;

    public ?string $company = null;

    public ?string $street = null;

    public ?string $town = null;

    public ?string $postalCode = null;

    public ?string $country = null;

    public ?string $cellphone = null;

    public ?string $phone = null;

    public ?string $language = null;

    /**
     * Custom notes
     */
    public ?string $notes = null;

    /**
     * Allowed values: "M,F,NULL"
     */
    public ?string $gender = null;

    /**
     * 0 if Contact is OK, 1 if Contact does not want to receive any of your e-mails anymore. This flag will stop
     * further campaigns. Be careful, setting this value to 1 will also un-subscribe contact from all lists. It is
     * recommended not to send this parameter at all if you do not know what you are doing.
     */
    public ?int $blacklisted = null;

    /**
     * Date of Contact's nameday in YYYY-MM-DD 00:00:00 format
     */
    public ?string $nameDay = null;

    /**
     * Date of Contact's birthday in YYYY-MM-DD 00:00:00 format
     */
    public ?string $birthday = null;

    /**
     * Contact lists presence of imported contacts. Any contact list presence unlisted in imported data will be
     * untouched. Unsubscribed contacts will stay unsubscribed if settings.preserve_unsubscribed=1
     */
    protected ContactLists $contactLists;

    /**
     * Custom fields belonging to contact Custom fields unlisted in imported data will be untouched.
     */
    protected CustomFieldValues $customFields;

    /**
     * Processing purposes assigned to contact. Every purpose may be assigned multiple times for different time
     * intervals. Exact duplicities will be silently skipped.
     */
    protected Purposes $purposes;

    public function __construct(?string $emailAddress = null)
    {
        $this->emailAddress = $emailAddress;
        $this->customFields = new CustomFieldValues();
        $this->contactLists = new ContactLists();
        $this->purposes = new Purposes();
    }

    public function setEmailAddress(?string $emailAddress): self
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;
        return $this;
    }

    public function setTitlesBefore(?string $titlesBefore): self
    {
        $this->titlesBefore = $titlesBefore;
        return $this;
    }

    public function setTitlesAfter(?string $titlesAfter): self
    {
        $this->titlesAfter = $titlesAfter;
        return $this;
    }

    public function setSalutation(?string $salutation): self
    {
        $this->salutation = $salutation;
        return $this;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;
        return $this;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;
        return $this;
    }

    public function setTown(?string $town): self
    {
        $this->town = $town;
        return $this;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function setCellphone(?string $cellphone): self
    {
        $this->cellphone = $cellphone;
        return $this;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;
        return $this;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * Allowed values: "M,F,NULL"
     */
    public function setGender(?string $gender): self
    {
        InvalidFormatException::checkInArray($gender, [self::MALE, self::FEMALE, null]);

        $this->gender = $gender;
        return $this;
    }

    /**
     * 0 (false) if Contact is OK, 1 (true) if Contact does not want to receive any of your e-mails anymore. This flag
     * will stop further campaigns. Be careful, setting this value to 1 will also un-subscribe contact from all lists.
     * It is recommended not to send this parameter at all if you do not know what you are doing.
     *
     * @param 0|1|boolean $blacklisted
     */
    public function setBlacklisted($blacklisted = true): self
    {
        $this->blacklisted = (int) $blacklisted;
        return $this;
    }

    /**
     * Date of Contact's birthday in YYYY-MM-DD 00:00:00 or different format
     *
     * @param bool        $convertToValidFormat converts the value to valid format
     */
    public function setNameDay(?string $nameDay, bool $convertToValidFormat = true): self
    {
        $this->nameDay = $this->convertDate($nameDay, $convertToValidFormat);
        return $this;
    }

    /**
     * Date of Contact's birthday in YYYY-MM-DD 00:00:00 format  or different format
     *
     * @param bool        $convertToValidFormat converts the value to valid format
     */
    public function setBirthday(?string $birthday, bool $convertToValidFormat = true): self
    {
        $this->birthday = $this->convertDate($birthday, $convertToValidFormat);
        return $this;
    }

    public function contactList(): ContactLists
    {
        return $this->contactLists;
    }

    public function customFields(): CustomFieldValues
    {
        return $this->customFields;
    }

    public function purposes(): Purposes
    {
        return $this->purposes;
    }

    /**
     * @return array{emailaddress: string|null, name: string|null, surname: string|null, titlesbefore: string|null, titlesafter: string|null, salution: string|null, company: string|null, street: string|null, town: string|null, postalcode: string|null, country: string|null, cellphone: string|null, phone: string|null, language: string|null, notes: string|null, gender: string|null, blacklisted: int|null, nameday: string|null, birthday: string|null, contactlists: ContactLists, customfields: CustomFieldValues, purposes: Purposes}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
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
            'customfields' => $this->customFields,
            'purposes' => $this->purposes,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->removeEmptyValues($this->toArray());
    }

    /**
     * @return static
     */
    public static function fromJSON(\stdClass $json): object
    {
        $item = parent::fromJSON($json);

        foreach ($json->contactlists as $value) {
            $item->contactLists->add(ContactListStatus::fromJSON($value));
        }

        if (isset($json->customfields)) {
            foreach ($json->customfields as $value) {
                $item->customFields->add(CustomFieldValue::fromJSON($value));
            }
        }

        return $item;
    }
}
