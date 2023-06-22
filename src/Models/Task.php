<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

class Task extends Model
{
    protected ?Recipient $recipient = null;

    /**
     * @var Replace[]
     */
    protected array $replace = [];

    /**
     * @var Attachment[]
     */
    protected array $attachments = [];

    private ?TemplateVariable $templateVariables = null;

    public function getRecipient(): ?Recipient
    {
        return $this->recipient;
    }

    public function setRecipient(Recipient $recipient): self
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @return Replace[]
     */
    public function getReplace(): array
    {
        return $this->replace;
    }

    public function addReplace(Replace $replace): self
    {
        $this->replace[] = $replace;
        return $this;
    }

    public function getTemplateVariables(): ?TemplateVariable
    {
        return $this->templateVariables;
    }

    public function setTemplateVariables(TemplateVariable $templateVariables): self
    {
        $this->templateVariables = $templateVariables;
        return $this;
    }

    /**
     * @return Attachment[]
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function addAttachment(Attachment $attachment): self
    {
        $this->attachments[] = $attachment;
        return $this;
    }

    /**
     * @return array{recipient: (Recipient | null), replace: Replace[], template_variables: (TemplateVariable | null), attachments: Attachment[]}
     */
    public function toArray(): array
    {
        return [
            'recipient' => $this->getRecipient(),
            'replace' => $this->getReplace(),
            'template_variables' => $this->getTemplateVariables(),
            'attachments' => $this->getAttachments(),
        ];
    }
}
