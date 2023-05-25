<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Contacts\Search;

use SmartEmailing\v3\Endpoints\AbstractFilters;
use SmartEmailing\v3\Exceptions\InvalidFormatException;
use SmartEmailing\v3\Models\Contact;

class ContactsSearchFilters extends AbstractFilters
{
    /**
     * @param int|numeric-string $byId
     */
    public function byId($byId): self
    {
        $this->filters['id'] = (int) $byId;
        return $this;
    }

    public function byLanguage(?string $byLanguage): self
    {
        $this->filters['language'] = $byLanguage;
        return $this;
    }

    public function byBlacklisted(?bool $byBlacklisted): self
    {
        $this->filters['blacklisted'] = $byBlacklisted;
        return $this;
    }

    public function byEmailAddress(?string $byEmailAddress): self
    {
        $this->filters['emailaddress'] = $byEmailAddress;
        return $this;
    }

    public function byName(?string $byName): self
    {
        $this->filters['name'] = $byName;
        return $this;
    }

    public function bySurname(?string $bySurname): self
    {
        $this->filters['surname'] = $bySurname;
        return $this;
    }

    public function byTitlesBefore(?string $byTitlesBefore): self
    {
        $this->filters['titlesbefore'] = $byTitlesBefore;
        return $this;
    }

    public function byTitlesAfter(?string $byTitlesAfter): self
    {
        $this->filters['titlesafter'] = $byTitlesAfter;
        return $this;
    }

    public function byBirthday(?string $byBirthday): self
    {
        $this->filters['birthday'] = $byBirthday;
        return $this;
    }

    public function byNameDay(?string $byNameDay): self
    {
        $this->filters['nameday'] = $byNameDay;
        return $this;
    }

    public function bySalution(?string $bySalution): self
    {
        $this->filters['salution'] = $bySalution;
        return $this;
    }

    public function byGender(?string $byGender): self
    {
        InvalidFormatException::checkInArray($byGender, [Contact::MALE, Contact::FEMALE, null]);
        $this->filters['gender'] = $byGender;
        return $this;
    }

    public function byCompany(?string $byCompany): self
    {
        $this->filters['company'] = $byCompany;
        return $this;
    }

    public function byStreet(?string $byStreet): self
    {
        $this->filters['street'] = $byStreet;
        return $this;
    }

    public function byTown(?string $byTown): self
    {
        $this->filters['town'] = $byTown;
        return $this;
    }

    public function byCountry(?string $byCountry): self
    {
        $this->filters['country'] = $byCountry;
        return $this;
    }

    public function byPostalCode(?string $byPostalCode): self
    {
        $this->filters['postalcode'] = $byPostalCode;
        return $this;
    }

    public function byNotes(?string $byNotes): self
    {
        $this->filters['notes'] = $byNotes;
        return $this;
    }

    public function byPhone(?string $byPhone): self
    {
        $this->filters['phone'] = $byPhone;
        return $this;
    }

    public function byCellPhone(?string $byCellPhone): self
    {
        $this->filters['cellphone'] = $byCellPhone;
        return $this;
    }

    public function byRealName(?string $byRealName): self
    {
        $this->filters['realname'] = $byRealName;
        return $this;
    }
}
