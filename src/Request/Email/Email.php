<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Email;

use SmartEmailing\v3\Api;
use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Request\AbstractRequest;

class Email extends AbstractRequest implements \JsonSerializable
{

    /** @var string */
    private $title;

    /** @var string|null */
    private $name;

    /** @var string|null */
    private $htmlBody;

    /** @var string|null */
    private $textBody;

    public function __construct(Api $api, string $title)
    {
        parent::__construct($api);
        $this->title = $title;
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

    protected function endpoint(): string
    {
        return 'emails';
    }

    protected function method()
    {
        return 'POST';
    }

    protected function options(): array
    {
        if ($this->htmlBody === null && $this->textBody === null) {
            throw new PropertyRequiredException("At least one of the properties must be set: 'htmlBody', 'textBody'");
        }

        return [
            'json' => $this->jsonSerialize()
        ];
    }

    private function toArray(): array
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

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

}
