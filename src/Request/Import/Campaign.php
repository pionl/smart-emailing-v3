<?php
namespace SmartEmailing\v3\Request\Import;

use SmartEmailing\v3\Models\Model;
use SmartEmailing\v3\Request\Send\Replace;

/**
 * Class Campaign
 *
 * Campaign for DoubleOptInSettings.
 *
 * @package SmartEmailing\v3\Request\Import
 */
class Campaign extends Model
{
    //region Properties
    /**
     * ID of E-mail containing {{confirmlink}}.
     *
     * @var int
     */
    private $emailId = null;
    /**
     * From e-mail address of opt-in campaign
     *
     * @var string
     */
    private $from = true;
    /**
     * Reply-To e-mail address in opt-in campaign
     *
     * @var string
     */
    private $replyTo = true;
    /**
     * From name of opt-in campaign
     *
     * @var string
     */
    private $senderName = true;
    /**
     * URL of thank-you page where contact will be redirected after clicking at confirmation link. If not provided,
     * contact will be redirected to default page
     *
     * Default value: null
     * @var bool
     */
    private $confirmationThankYouPageUrl = null;

    /**
     * Date and time in YYYY-MM-DD HH:MM:SS format, when double opt-in e-mail will be expired.
     * 
     * @var string
     */
    private $validTo = null;
    
    /** @var Replace[] */
    private $replace = [];
    //endregion

    //region Setters
    /**
     * Campaign constructor.
     *
     * @param int           $emailId
     * @param string        $from
     * @param string        $replyTo
     * @param string        $senderName
     * @param string|null   $confirmationThankYouPageUrl
     * @param string|null   $validTo
     * @param Replace[]     $replace
     */
    public function __construct($emailId, $from, $replyTo, $senderName, $confirmationThankYouPageUrl = null, $validTo = null, $replace = [])
    {
        $this->emailId = $emailId;
        $this->from = $from;
        $this->replyTo = $replyTo;
        $this->senderName = $senderName;
        $this->confirmationThankYouPageUrl = $confirmationThankYouPageUrl;
        $this->validTo = $validTo;
        $this->replace = $replace;
    }

    /**
     * URL of thank-you page where contact will be redirected after clicking at confirmation link. If not provided,
     * contact will be redirected to default page
     *
     * @param string $confirmationThankYouPageUrl
     *
     * @return Campaign
     */
    public function setConfirmationThankYouPageUrl($confirmationThankYouPageUrl)
    {
        $this->confirmationThankYouPageUrl = $confirmationThankYouPageUrl;
        return $this;
    }

    /**
     * @return Replace[]
     */
    public function getReplace(): array
    {
        return $this->replace;
    }

    /**
     * Dynamic content used to preprocess template before rendering it. This can be used to modify template structure and may contain HTML, dynamic fields and template scripts.
     * 
     * @param Replace $replace
     * 
     * @return void
     */
    public function addReplace(Replace $replace): void
    {
        $this->replace[] = $replace;
    }
    //endregion

    /**
     * Converts the settings to array
     * @return array
     */
    public function toArray()
    {
        return [
            'email_id' => $this->emailId,
            'sender_credentials' => [
                'from' => $this->from,
                'reply_to' => $this->replyTo,
                'sender_name' => $this->senderName
            ],
            'confirmation_thank_you_page_url' => $this->confirmationThankYouPageUrl,
            'valid_to' => $this->validTo,
            'replace' => $this->getReplace()
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        // Don't remove any null/empty array - not needed
        return $this->toArray();
    }

}
