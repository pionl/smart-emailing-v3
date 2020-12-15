<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Send;

use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Models\Model;

class Attachment extends Model
{
	/** @var string */
	private $fileName;

	/** @var string */
	private $contentType;

	/** @var string */
	private $dataBase64;


	public function getFileName(): ?String
	{
		return $this->fileName;
	}

	public function setFileName(String $fileName): void
	{
		$this->fileName = $fileName;
	}

	public function getContentType(): ?String
	{
		return $this->contentType;
	}

	public function setContentType(String $contentType): void
	{
		$this->contentType = $contentType;
	}

	public function getDataBase64(): ?String
	{
		return $this->dataBase64;
	}

	public function setDataBase64(String $dataBase64): void
	{
		$this->dataBase64 = $dataBase64;
	}

	public function toArray(): array
	{
		PropertyRequiredException::throwIf('file_name', !empty($this->getFileName()), 'You must set file_name - missing file_name');
		PropertyRequiredException::throwIf('content_type', !empty($this->getContentType()), 'You must set content_type - missing content_type');
		PropertyRequiredException::throwIf('data_base64', !empty($this->getDataBase64()), 'You must set data_base64 - missing data_base64');

		return [
			'file_name' => $this->getFileName(),
			'content_type' => $this->getContentType(),
			'data_base64' => $this->getDataBase64(),
		];
	}

	public function jsonSerialize(): array
	{
		return $this->toArray();
	}
}
