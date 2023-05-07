<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Models;

use SmartEmailing\v3\Exceptions\PropertyRequiredException;

class MessageContents extends Model
{
    private ?string $subject = null;

    private ?string $htmlBody = null;

    private ?string $textBody = null;

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(String $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    public function getHtmlBody(): ?string
    {
        return $this->htmlBody;
    }

    public function setHtmlBody(String $htmlBody): self
    {
        $this->htmlBody = $htmlBody;
        return $this;
    }

    public function getTextBody(): ?string
    {
        return $this->textBody;
    }

    public function setTextBody(String $textBody): self
    {
        $this->textBody = $textBody;
        return $this;
    }

    /**
     * @return array{subject: string|null, html_body: string|null, text_body: string|null}
     */
    public function toArray(): array
    {
        PropertyRequiredException::throwIf(
            'subject',
            empty($this->getSubject()) === false,
            'You must set subject - missing subject'
        );
        PropertyRequiredException::throwIf(
            'html_body',
            empty($this->getHtmlBody()) === false,
            'You must set html_body - missing html_body'
        );
        PropertyRequiredException::throwIf(
            'text_body',
            empty($this->getTextBody()) === false,
            'You must set text_body - missing text_body'
        );

        return [
            'subject' => $this->getSubject(),
            'html_body' => $this->getHtmlBody(),
            'text_body' => $this->getTextBody(),
        ];
    }
}
