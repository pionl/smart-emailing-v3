<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Send;

use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Models\Model;

class MessageContents extends Model
{
	/** @var string */
	private $subject;

	/** @var string */
	private $htmlBody;

	/** @var string */
	private $textBody;

	public function getSubject(): ?String
	{
		return $this->subject;
	}

	public function setSubject(String $subject): void
	{
		$this->subject = $subject;
	}

	public function getHtmlBody(): ?String
	{
		return $this->htmlBody;
	}

	public function setHtmlBody(String $htmlBody): void
	{
		$this->htmlBody = $htmlBody;
	}

	public function getTextBody(): ?String
	{
		return $this->textBody;
	}

	public function setTextBody(String $textBody): void
	{
		$this->textBody = $textBody;
	}

	public function toArray(): array
	{
		PropertyRequiredException::throwIf('subject', !empty($this->getSubject()), 'You must set subject - missing subject');
		PropertyRequiredException::throwIf('html_body', !empty($this->getHtmlBody()), 'You must set html_body - missing html_body');
		PropertyRequiredException::throwIf('text_body', !empty($this->getTextBody()), 'You must set text_body - missing text_body');

		return [
			'subject' => $this->getSubject(),
			'html_body' => $this->getHtmlBody(),
			'text_body' => $this->getTextBody(),
		];
	}

	public function jsonSerialize(): array
	{
		return $this->toArray();
	}
}
