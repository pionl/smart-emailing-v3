<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Send;

use JsonSerializable;
use SmartEmailing\v3\Request\AbstractRequest;

/**
 * Class TransactionalEmails
 * @author Michal Kubec
 *
 * The implementation of API call:
 * @link https://app.smartemailing.cz/docs/api/v3/index.html#api-Custom_campaigns-Send_transactional_emails
 */
class TransactionalEmails  extends AbstractRequest implements JsonSerializable
{
	/** @var SenderCredentials */
	private $senderCredentials;

	/** @var string */
	private $tag;

	/** @var int  */
	private $emailId;

	/** @var MessageContents */
	private $messageContents;

	/** @var Task[] */
	private $tasks = [];

	public function getSenderCredentials(): ?SenderCredentials
	{
		return $this->senderCredentials;
	}

	public function setSenderCredentials(SenderCredentials $senderCredentials): void
	{
		$this->senderCredentials = $senderCredentials;
	}

	public function getTag(): ?String
	{
		return $this->tag;
	}

	public function setTag(String $tag): void
	{
		$this->tag = $tag;
	}

	/**
	 * @return int
	 */
	public function getEmailId(): ?Int
	{
		return $this->emailId;
	}

	public function setEmailId(Int $emailId): void
	{
		$this->emailId = $emailId;
	}

	public function getMessageContents(): ?MessageContents
	{
		return $this->messageContents;
	}

	public function setMessageContents(MessageContents $messageContents): void
	{
		$this->messageContents = $messageContents;
	}

	/**
	 * @return Task[]
	 */
	public function getTasks(): array
	{
		return $this->tasks;
	}

	public function addTask(Task $task): void
	{
		$this->tasks[] = $task;
	}

	protected function endpoint(): String
	{
		return 'send/transactional-emails-bulk';
	}

	protected function options(): array
	{
		return [
			'json' => $this->jsonSerialize()
		];
	}

	protected function method(): String
	{
		return 'POST';
	}

	public function toArray(): array
	{
		return [
			'sender_credentials' => $this->getSenderCredentials(),
			'tag' => $this->getTag(),
			'email_id' => $this->getEmailId(),
			'message_contents' => $this->getMessageContents(),
			'tasks' => $this->getTasks(),
		];
	}

	public function jsonSerialize(): array
	{
		return $this->toArray();
	}
}
