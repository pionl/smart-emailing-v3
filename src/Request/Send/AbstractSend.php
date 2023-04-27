<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Send;

use JsonSerializable;
use SmartEmailing\v3\Request\AbstractRequest;

/**
 * Class TransactionEmails
 *
 * @link https://app.smartemailing.cz/docs/api/v3/index.html#api-Custom_campaigns-Send_transactional_emails
 */
abstract class AbstractSend extends AbstractRequest implements JsonSerializable
{
	/** @var SenderCredentials */
	protected $senderCredentials;
	/** @var int */
	protected $emailId;
	/** @var string */
	protected $tag;
	/** @var Task[] */
	protected $tasks = [];

	/**
	 * @return int
	 */
	public function getEmailId(): ?int
	{
		return $this->emailId;
	}

	/**
	 * @param int $emailId
	 */
	public function setEmailId(int $emailId): void
	{
		$this->emailId = $emailId;
	}

	/**
	 * @return string
	 */
	public function getTag(): ?String
	{
		return $this->tag;
	}

	/**
	 * @param string $tag
	 */
	public function setTag(string $tag): void
	{
		$this->tag = $tag;
	}

	/**
	 * @return SenderCredentials
	 */
	public function getSenderCredentials(): ?SenderCredentials
	{
		return $this->senderCredentials;
	}

	/**
	 * @param SenderCredentials $senderCredentials
	 */
	public function setSenderCredentials(SenderCredentials $senderCredentials): void
	{
		$this->senderCredentials = $senderCredentials;
	}

	/**
	 * @param Task $task
	 */
	public function addTask(Task $task): void
	{
		$this->tasks[] = $task;
	}

	/**
	 * @return Task[]
	 */
	public function getTasks(): array
	{
		return $this->tasks;
	}

	public function jsonSerialize(): array
	{
		return $this->toArray();
	}

	protected function options()
	{
		return [
			'json' => $this->jsonSerialize(),
		];
	}

	protected function method()
	{
		return 'POST';
	}
}
