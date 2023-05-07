<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Endpoints\Email\Create;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Endpoints\AbstractRequest;
use SmartEmailing\v3\Endpoints\StatusResponse;
use SmartEmailing\v3\Exceptions\PropertyRequiredException;

/**
 * @extends AbstractRequest<StatusResponse>
 */
class EmailCreateRequest extends AbstractRequest
{
    private string $title;

    private ?string $name = null;

    private ?string $htmlBody = null;

    private ?string $textBody = null;

    public function __construct(Api $api, string $title)
    {
        parent::__construct($api);
        $this->title = $title;
    }

    public function setTitle(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setHtmlBody(string $htmlBody): self
    {
        $this->htmlBody = $htmlBody;
        return $this;
    }

    public function setTextBody(string $textBody): self
    {
        $this->textBody = $textBody;
        return $this;
    }

    /**
     * @return array{title: string, name: string, htmlbody?: string, textbody?: string}
     */
    public function toArray(): array
    {
        $data = [
            'title' => $this->title,
            'name' => $this->name ?: $this->title,
        ];

        if ($this->htmlBody) {
            $data['htmlbody'] = $this->htmlBody;
        }

        if ($this->textBody) {
            $data['textbody'] = $this->textBody;
        }

        return $data;
    }

    protected function endpoint(): string
    {
        return 'emails';
    }

    protected function method(): string
    {
        return 'POST';
    }

    protected function options(): array
    {
        if ($this->htmlBody === null && $this->textBody === null) {
            throw new PropertyRequiredException("At least one of the properties must be set: 'htmlBody', 'textBody'");
        }
        return parent::options();
    }
}
