<?php
namespace SmartEmailing\v3\Request\Import;

use SmartEmailing\v3\Models\Model;

/**
 * Class DoubleOptInSettings
 *
 * DoubleOptInSettings for Settings.
 *
 * @package SmartEmailing\v3\Request\Import
 */
class DoubleOptInSettings extends Model
{
    //region Properties
    /**
     * Double-opt-in e-mail settings
     *
     * @var Campaign|null
     */
    private $campaign = null;
    /**
     * By adding silence period you will not send double opt-in e-mail to any e-mailmaddress that recieved any opt-in e-mail in specified period.
     *
     * Note: to prevent double opt-in spam, silence_period is now added to double_opt_in_settings by default (if not already provided) and set to 1 day.
     *
     * @var SilencePeriod|null
     */
    private $silencePeriod = null;
    /**
     * Double-opt in send-to mode. Fill-in all to send double opt-in e-email to every contact in batch, new-in-database to send to send double opt-in e-email only to contacts that do not exist in the database yet.
     *
     * Default value: 'all'
     * @var string
     */
    private $sendToMode = 'all';
    //endregion

    //region Setters
    /**
     * DoubleOptInSettings constructor.
     *
     * @param Campaign      $campaign
     * @param SilencePeriod $silencePeriod
     * @param string        $sendToMode
     */
    public function __construct(Campaign $campaign, SilencePeriod $silencePeriod = null, $sendToMode = 'all')
    {
        $this->campaign = $campaign;
        $this->silencePeriod = $silencePeriod;
        $this->sendToMode = $sendToMode;
    }

    /**
     * By adding silence period you will not send double opt-in e-mail to any e-mailmaddress that recieved any opt-in e-mail in specified period.
     *
     * Note: to prevent double opt-in spam, silence_period is now added to double_opt_in_settings by default (if not already provided) and set to 1 day.
     *
     * @param SilencePeriod $silencePeriod
     *
     * @return DoubleOptInSettings
     */
    public function setSilencePeriod(SilencePeriod $silencePeriod)
    {
        $this->silencePeriod = $silencePeriod;
        return $this;
    }

    /**
     * Double-opt in send-to mode. Fill-in all to send double opt-in e-email to every contact in batch, new-in-database to send to send double opt-in e-email only to contacts that do not exist in the database yet.
     *
     * Default value: 'all'
     *
     * @param string $sendToMode
     *
     * @return DoubleOptInSettings
     */
    public function setSendToMode($sendToMode)
    {
        $this->sendToMode = $sendToMode;
        return $this;
    }
    //endregion

    /**
     * Converts the settings to array
     * @return array
     */
    public function toArray()
    {
        return [
            'campaign' => $this->campaign,
            'silence_period' => $this->silencePeriod,
            'send_to_mode' => $this->sendToMode
        ];
    }

    public function jsonSerialize(): array
    {
        // Don't remove any null/empty array - not needed
        return $this->toArray();
    }

}
