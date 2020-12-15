<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Send;

use SmartEmailing\v3\Models\Model;

class TemplateVariable extends Model
{
	/** @var array */
	private $customData = [];

	public function getCustomData(): array
	{
		return $this->customData;
	}

	public function setCustomData(array $customData): void
	{
		$this->customData = $customData;
	}

	public function toArray(): array
	{
		return $this->getCustomData();
	}

	public function jsonSerialize(): array
	{
		return $this->toArray();
	}
}
