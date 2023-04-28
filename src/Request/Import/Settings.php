<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Request\Import;

use SmartEmailing\v3\Models\Model;

/**
 * Class Settings
 *
 * Settings for import.
 */
class Settings extends Model
{
    //region Properties
    /**
     * Existing contact's defaultfields and customfields (including nameday, gender and salution) will be updated ONLY
     * if this is set to true. If false is provided, only contactlist statuses will be updated. Nothing else.
     *
     * Default value: true
     *
     * @var bool
     */
    public $update = true;

    /**
     * If name is provided in Contact data section, automatically generate nameday defaultfield and overwrite existing
     * value if any.
     *
     * Default value: true
     *
     * @var bool
     */
    public $addNameDays = true;

    /**
     * If name is provided in Contact data section, automatically generate gender defaultfield and overwrite existing
     * value if any.
     *
     * Default value: true
     *
     * @var bool
     */
    public $addGenders = true;

    /**
     * If name is provided in Contact data section, automatically generate salution defaultfield and overwrite existing
     * value if any.
     *
     * Default value: true
     *
     * @var bool
     */
    public $addSalutations = true;

    /**
     * If this flag is set to true, all contacts that are unsubscribed in some lists will stay unsubscribed regardless
     * of imported statuses. This is very useful when Import should respect unsubscriptions from previous campaigns and
     * we strongly recommend to keep this turned on.
     *
     * Default value: true
     *
     * @var bool
     */
    public $preserveUnSubscribed = true;

    /**
     * If this flag is set to true, all contacts with invalid e-mail addresses will be silently skipped and your Import
     * will finish without them. Otherwise it will be terminated with 422 Error.
     *
     * Default value: false
     *
     * @var bool
     */
    public $skipInvalidEmails = false;

    /**
     * If this section is present, opt-in e-mail will be sent to all contacts in request, excluding blacklisted (sending
     * opt-in e-amil to blacklisted contacts can be forced by setting preserve_unsubscribed=false). Imported data will
     * be written when they click through confirmation link.
     *
     * Default value: null
     *
     * @var DoubleOptInSettings|null
     */
    public $doubleOptInSettings = null;

    //endregion

    //region Setters
    /**
     * Existing contact's defaultfields and customfields (including nameday, gender and salution) will be updated ONLY
     * if this is set to true. If false is provided, only contactlist statuses will be updated. Nothing else.
     *
     * @param bool $update default value: true
     *
     * @return Settings
     */
    public function setUpdate($update)
    {
        $this->update = $update;
        return $this;
    }

    /**
     * If name is provided in Contact data section, automatically generate nameday defaultfield and overwrite existing
     * value if any.
     *
     * @param bool $addNameDays Default value: true
     *
     * @return Settings
     */
    public function setAddNameDays($addNameDays)
    {
        $this->addNameDays = $addNameDays;
        return $this;
    }

    /**
     * If name is provided in Contact data section, automatically generate gender defaultfield and overwrite existing
     * value if any.
     *
     * @param bool $addGenders Default value: true
     *
     * @return Settings
     */
    public function setAddGenders($addGenders)
    {
        $this->addGenders = $addGenders;
        return $this;
    }

    /**
     * If name is provided in Contact data section, automatically generate nameday defaultfield and overwrite existing
     * value if any.
     *
     * @param bool $addSalutations Default value: true
     *
     * @return Settings
     */
    public function setAddSalutations($addSalutations)
    {
        $this->addSalutations = $addSalutations;
        return $this;
    }

    /**
     * If this flag is set to true, all contacts that are unsubscribed in some lists will stay unsubscribed regardless
     * of imported statuses. This is very useful when Import should respect unsubscriptions from previous campaigns and
     * we strongly recommend to keep this turned on.
     *
     * @param bool $preserveUnSubscribed Default value: true
     *
     * @return Settings
     */
    public function setPreserveUnSubscribed($preserveUnSubscribed)
    {
        $this->preserveUnSubscribed = $preserveUnSubscribed;
        return $this;
    }

    /**
     * Existing contact's defaultfields and customfields (including nameday, gender and salution) will be updated ONLY
     * if this is set to true. If false is provided, only contactlist statuses will be updated. Nothing else.
     *
     * @param bool $skipInvalidEmails Default value: false
     *
     * @return Settings
     */
    public function setSkipInvalidEmails($skipInvalidEmails)
    {
        $this->skipInvalidEmails = $skipInvalidEmails;
        return $this;
    }

    /**
     * If this section is present, opt-in e-mail will be sent to all contacts in request, excluding blacklisted (sending
     * opt-in e-amil to blacklisted contacts can be forced by setting preserve_unsubscribed=false). Imported data will
     * be written when they click through confirmation link.
     *
     * @param DoubleOptInSettings $doubleOptInSettings Default value: null
     *
     * @return Settings
     */
    public function setDoubleOptInSettings($doubleOptInSettings)
    {
        $this->doubleOptInSettings = $doubleOptInSettings;
        return $this;
    }

    //endregion

    /**
     * Converts the settings to array
     *
     * @return array
     */
    public function toArray()
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

    public function jsonSerialize(): array
    {
        // Don't remove any null/empty array - not needed
        return $this->toArray();
    }
}
