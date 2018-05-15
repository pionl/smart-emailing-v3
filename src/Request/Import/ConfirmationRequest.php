<?php
namespace SmartEmailing\v3\Request\Import;

use SmartEmailing\v3\Models\Model;

/**
 * Class ConfirmationRequest
 *
 * ConfirmationRequest for Settings.
 *
 * @author Stanislav Janů info@lweb.cz
 * @package SmartEmailing\v3\Request\Import
 */
class ConfirmationRequest extends Model
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
    public $confirmationThankYouPageUrl = null;
    //endregion

    //region Setters
    /**
     * ConfirmationRequest constructor.
     *
     * @param int           $emailId
     * @param string        $from
     * @param string        $replyTo
     * @param string        $senderName
     * @param string|null   $confirmationThankYouPageUrl
     */
    public function __construct($emailId, $from, $replyTo, $senderName, $confirmationThankYouPageUrl = null)
    {
        $this->emailId = $emailId;
        $this->from = $from;
        $this->replyTo = $replyTo;
        $this->senderName = $senderName;
        $this->confirmationThankYouPageUrl = $confirmationThankYouPageUrl;
    }

    /**
     * URL of thank-you page where contact will be redirected after clicking at confirmation link. If not provided,
     * contact will be redirected to default page
     *
     * @param string $confirmationThankYouPageUrl
     *
     * @return ConfirmationRequest
     */
    public function setConfirmationThankYouPageUrl($confirmationThankYouPageUrl)
    {
        $this->confirmationThankYouPageUrl = $confirmationThankYouPageUrl;
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
            'email_id' => $this->emailId,
            'sender_credentials' => [
                'from' => $this->from,
                'reply_to' => $this->replyTo,
                'sender_name' => $this->senderName
            ],
            'confirmation_thank_you_page_url' => $this->confirmationThankYouPageUrl
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
