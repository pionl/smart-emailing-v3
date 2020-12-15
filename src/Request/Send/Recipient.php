<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Send;

use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Models\Model;


class Recipient extends Model
{
	/** @var string */
	private $emailAddress;

	public function getEmailAddress(): ?String
	{
		return $this->emailAddress;
	}

	public function setEmailAddress(String $emailAddress): void
	{
		$this->emailAddress = $emailAddress;
	}

	public function toArray(): array
	{
		PropertyRequiredException::throwIf('emailaddress', !empty($this->getEmailAddress()), 'You must set emailaddress - missing emailaddress');

		return [
			'emailaddress' => $this->getEmailAddress(),
		];
	}

	public function jsonSerialize(): array
	{
		return $this->toArray();
	}
}
