<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

/**
 * DoubleOptInSettings for Settings.
 */
class DoubleOptInSettings extends Model
{
    /**
     * Double-opt-in e-mail settings
     */
    protected ?Campaign $campaign = null;

    /**
     * By adding silence period you will not send double opt-in e-mail to any e-mailmaddress that recieved any opt-in
     * e-mail in specified period.
     *
     * Note: to prevent double opt-in spam, silence_period is now added to double_opt_in_settings by default (if not
     * already provided) and set to 1 day.
     */
    protected ?SilencePeriod $silencePeriod = null;

    /**
     * Double-opt in send-to mode. Fill-in all to send double opt-in e-email to every contact in batch, new-in-database
     * to send to send double opt-in e-email only to contacts that do not exist in the database yet.
     */
    protected string $sendToMode = 'all';

    public function __construct(Campaign $campaign, SilencePeriod $silencePeriod = null, string $sendToMode = 'all')
    {
        $this->campaign = $campaign;
        $this->silencePeriod = $silencePeriod;
        $this->sendToMode = $sendToMode;
    }

    /**
     * By adding silence period you will not send double opt-in e-mail to any e-mailmaddress that recieved any opt-in
     * e-mail in specified period.
     *
     * Note: to prevent double opt-in spam, silence_period is now added to double_opt_in_settings by default (if not
     * already provided) and set to 1 day.
     */
    public function setSilencePeriod(SilencePeriod $silencePeriod): self
    {
        $this->silencePeriod = $silencePeriod;
        return $this;
    }

    /**
     * Double-opt in send-to mode. Fill-in all to send double opt-in e-email to every contact in batch, new-in-database
     * to send to send double opt-in e-email only to contacts that do not exist in the database yet.
     */
    public function setSendToMode(string $sendToMode): self
    {
        $this->sendToMode = $sendToMode;
        return $this;
    }

    /**
     * Converts the settings to array
     *
     * @return array{campaign: Campaign|null, silence_period: SilencePeriod|null, send_to_mode: string}
     */
    public function toArray(): array
    {
        return [
            'campaign' => $this->campaign,
            'silence_period' => $this->silencePeriod,
            'send_to_mode' => $this->sendToMode,
        ];
    }
}
