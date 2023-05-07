<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

/**
 * Settings for import.
 */
class ImportContactsSettings extends Model
{
    /**
     * Existing contact's defaultfields and customfields (including nameday, gender and salution) will be updated ONLY
     * if this is set to true. If false is provided, only contactlist statuses will be updated. Nothing else.
     */
    public bool $update = true;

    /**
     * If name is provided in Contact data section, automatically generate nameday defaultfield and overwrite existing
     * value if any.
     */
    public bool $addNameDays = true;

    /**
     * If name is provided in Contact data section, automatically generate gender defaultfield and overwrite existing
     * value if any.
     */
    public bool $addGenders = true;

    /**
     * If name is provided in Contact data section, automatically generate salution defaultfield and overwrite existing
     * value if any.
     */
    public bool $addSalutations = true;

    /**
     * If this flag is set to true, all contacts that are unsubscribed in some lists will stay unsubscribed regardless
     * of imported statuses. This is very useful when Import should respect unsubscriptions from previous campaigns and
     * we strongly recommend to keep this turned on.
     */
    public bool $preserveUnSubscribed = true;

    /**
     * If this flag is set to true, all contacts with invalid e-mail addresses will be silently skipped and your Import
     * will finish without them. Otherwise it will be terminated with 422 Error.
     */
    public bool $skipInvalidEmails = false;

    /**
     * If this section is present, opt-in e-mail will be sent to all contacts in request, excluding blacklisted (sending
     * opt-in e-amil to blacklisted contacts can be forced by setting preserve_unsubscribed=false). Imported data will
     * be written when they click through confirmation link.
     */
    public ?DoubleOptInSettings $doubleOptInSettings = null;

    /**
     * Existing contact's defaultfields and customfields (including nameday, gender and salution) will be updated ONLY
     * if this is set to true. If false is provided, only contactlist statuses will be updated. Nothing else.
     */
    public function setUpdate(bool $update = true): self
    {
        $this->update = $update;
        return $this;
    }

    /**
     * If name is provided in Contact data section, automatically generate nameday defaultfield and overwrite existing
     * value if any.
     */
    public function setAddNameDays(bool $addNameDays = true): self
    {
        $this->addNameDays = $addNameDays;
        return $this;
    }

    /**
     * If name is provided in Contact data section, automatically generate gender defaultfield and overwrite existing
     * value if any.
     */
    public function setAddGenders(bool $addGenders = true): self
    {
        $this->addGenders = $addGenders;
        return $this;
    }

    /**
     * If name is provided in Contact data section, automatically generate nameday defaultfield and overwrite existing
     * value if any.
     */
    public function setAddSalutations(bool $addSalutations = true): self
    {
        $this->addSalutations = $addSalutations;
        return $this;
    }

    /**
     * If this flag is set to true, all contacts that are unsubscribed in some lists will stay unsubscribed regardless
     * of imported statuses. This is very useful when Import should respect unsubscriptions from previous campaigns and
     * we strongly recommend to keep this turned on.
     */
    public function setPreserveUnSubscribed(bool $preserveUnSubscribed = true): self
    {
        $this->preserveUnSubscribed = $preserveUnSubscribed;
        return $this;
    }

    /**
     * Existing contact's defaultfields and customfields (including nameday, gender and salution) will be updated ONLY
     * if this is set to true. If false is provided, only contactlist statuses will be updated. Nothing else.
     */
    public function setSkipInvalidEmails(bool $skipInvalidEmails = true): self
    {
        $this->skipInvalidEmails = $skipInvalidEmails;
        return $this;
    }

    /**
     * If this section is present, opt-in e-mail will be sent to all contacts in request, excluding blacklisted (sending
     * opt-in e-amil to blacklisted contacts can be forced by setting preserve_unsubscribed=false). Imported data will
     * be written when they click through confirmation link.
     */
    public function setDoubleOptInSettings(DoubleOptInSettings $doubleOptInSettings): self
    {
        $this->doubleOptInSettings = $doubleOptInSettings;
        return $this;
    }

    /**
     * @return array{update: bool, add_namedays: bool, add_genders: bool, add_salutions: bool, preserve_unsubscribed: bool, skip_invalid_emails: bool, double_opt_in_settings: DoubleOptInSettings|null}
     */
    public function toArray(): array
    {
        return [
            'update' => $this->update,
            'add_namedays' => $this->addNameDays,
            'add_genders' => $this->addGenders,
            'add_salutions' => $this->addSalutations,
            'preserve_unsubscribed' => $this->preserveUnSubscribed,
            'skip_invalid_emails' => $this->skipInvalidEmails,
            'double_opt_in_settings' => $this->doubleOptInSettings,
        ];
    }
}
