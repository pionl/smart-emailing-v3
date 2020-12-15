<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Send;

use SmartEmailing\v3\Models\Model;

class Task extends Model
{
	/** @var Recipient */
	private $recipient;

	/** @var Replace[] */
	private $replace = [];

	/** @var TemplateVariable  */
	private $templateVariables;

	/** @var Attachment[] */
	private $attachments = [];

	public function getRecipient(): ?Recipient
	{
		return $this->recipient;
	}

	public function setRecipient(Recipient $recipient): void
	{
		$this->recipient = $recipient;
	}

	/**
	 * @return Replace[]
	 */
	public function getReplace(): array
	{
		return $this->replace;
	}

	public function addReplace(Replace $replace): void
	{
		$this->replace[] = $replace;
	}

	public function getTemplateVariables(): ?TemplateVariable
	{
		return $this->templateVariables;
	}

	public function setTemplateVariables(TemplateVariable $templateVariables): void
	{
		$this->templateVariables = $templateVariables;
	}

	/**
	 * @return Attachment[]
	 */
	public function getAttachments(): array
	{
		return $this->attachments;
	}

	public function addAttachment(Attachment $attachment): void
	{
		$this->attachments[] = $attachment;
	}

	public function toArray(): array
	{
		return [
			'recipient' => $this->getRecipient(),
			'replace' => $this->getReplace(),
			'template_variables' => $this->getTemplateVariables(),
			'attachments' => $this->getAttachments(),
		];
	}

	public function jsonSerialize(): array
	{
		return $this->toArray();
	}
}
