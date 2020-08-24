<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Send;

use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Models\Model;

class SenderCredentials extends Model
{
	private $from;
	private $replyTo;
	private $senderName;

	public function getFrom(): ?String
	{
		return $this->from;
	}

	public function setFrom(String $from): void
	{
		$this->from = $from;
	}

	public function getReplyTo(): ?String
	{
		return $this->replyTo;
	}

	public function setReplyTo(String $replyTo): void
	{
		$this->replyTo = $replyTo;
	}

	public function getSenderName(): ?String
	{
		return $this->senderName;
	}

	public function setSenderName(String $senderName): void
	{
		$this->senderName = $senderName;
	}

	public function toArray(): array
	{
		PropertyRequiredException::throwIf('from', !empty($this->getFrom()), 'You must set from - missing from');
		PropertyRequiredException::throwIf('reply_to', !empty($this->getReplyTo()), 'You must set reply_to - missing reply_to');
		PropertyRequiredException::throwIf('from', !empty($this->getSenderName()), 'You must set sender_name - missing sender_name');

		return [
			'from' => $this->getFrom(),
			'reply_to' => $this->getReplyTo(),
			'sender_name' => $this->getSenderName(),
		];
	}

	public function jsonSerialize(): array
	{
		return $this->toArray();
	}
}
