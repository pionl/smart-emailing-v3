<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Send;

use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Models\Model;


class Recipient extends Model
{
	/** @var string */
	private $emailAddress;
	/** @var string */
	private $cellphone = '';

	public function getEmailAddress(): ?string
	{
		return $this->emailAddress;
	}

	public function setEmailAddress(string $emailAddress): void
	{
		$this->emailAddress = $emailAddress;
	}

	/**
	 * @return string
	 */
	public function getCellphone(): string
	{
		return $this->cellphone;
	}

	/**
	 * @param string $cellphone
	 */
	public function setCellphone(string $cellphone): void
	{
		$this->cellphone = $cellphone;
	}

	public function toArray(): array
	{
		PropertyRequiredException::throwIf(
			'emailaddress',
			!empty($this->getEmailAddress()),
			'You must set emailaddress - missing emailaddress'
		);

		$data = [
			'emailaddress' => $this->getEmailAddress()
		];

		if (!empty($this->getCellphone())) {
			$data['cellphone'] = $this->getCellphone();
		}

		return $data;
	}

	public function jsonSerialize(): array
	{
		return $this->toArray();
	}
}
