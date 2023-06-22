<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

/**
 * Campaign for DoubleOptInSettings.
 */
class Campaign extends Model
{
    /**
     * ID of E-mail containing {{confirmlink}}.
     */
    protected int $emailId;

    /**
     * URL of thank-you page where contact will be redirected after clicking at confirmation link. If not provided,
     * contact will be redirected to default page
     */
    protected ?string $confirmationThankYouPageUrl = null;

    /**
     * Date and time in YYYY-MM-DD HH:MM:SS format, when double opt-in e-mail will be expired.
     */
    protected ?string $validTo = null;

    /**
     * @var Replace[]
     */
    protected array $replace = [];

    private SenderCredentials $senderCredentials;

    /**
     * @param Replace[]         $replace
     */
    public function __construct(
        int $emailId,
        SenderCredentials $senderCredentials,
        ?string $confirmationThankYouPageUrl = null,
        ?string $validTo = null,
        array $replace = []
    ) {
        $this->emailId = $emailId;
        $this->senderCredentials = $senderCredentials;
        $this->confirmationThankYouPageUrl = $confirmationThankYouPageUrl;
        $this->validTo = $validTo;
        $this->replace = $replace;
    }

    /**
     * Dynamic content used to preprocess template before rendering it. This can be used to modify template structure
     * and may contain HTML, dynamic fields and template scripts.
     */
    public function addReplace(Replace $replace): self
    {
        $this->replace[] = $replace;
        return $this;
    }

    public function getEmailId(): int
    {
        return $this->emailId;
    }

    public function setEmailId(int $emailId): self
    {
        $this->emailId = $emailId;
        return $this;
    }

    public function senderCredentials(): SenderCredentials
    {
        return $this->senderCredentials;
    }

    public function getConfirmationThankYouPageUrl(): ?string
    {
        return $this->confirmationThankYouPageUrl;
    }

    /**
     * URL of thank-you page where contact will be redirected after clicking at confirmation link. If not provided,
     * contact will be redirected to default page
     */
    public function setConfirmationThankYouPageUrl(string $confirmationThankYouPageUrl): self
    {
        $this->confirmationThankYouPageUrl = $confirmationThankYouPageUrl;
        return $this;
    }

    public function getValidTo(): ?string
    {
        return $this->validTo;
    }

    /**
     * Date and time in YYYY-MM-DD HH:MM:SS format, when double opt-in e-mail will be expired.
     */
    public function setValidTo(string $validTo): self
    {
        $this->validTo = $validTo;
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
     * Converts the settings to array
     *
     * @return array{email_id: int, sender_credentials: SenderCredentials|null, confirmation_thank_you_page_url: string|null, valid_to: string|null, replace: Replace[]}
     */
    public function toArray(): array
    {
        return [
            'email_id' => $this->emailId,
            'sender_credentials' => $this->senderCredentials,
            'confirmation_thank_you_page_url' => $this->confirmationThankYouPageUrl,
            'valid_to' => $this->validTo,
            'replace' => $this->getReplace(),
        ];
    }
}
