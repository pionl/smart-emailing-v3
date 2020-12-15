<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Send;

/**
 * Class TransactionalEmails
 *
 * @author Michal Kubec
 *
 * The implementation of API call:
 * @link https://app.smartemailing.cz/docs/api/v3/index.html#api-Custom_campaigns-Send_transactional_emails
 */
class TransactionalEmails extends AbstractSend
{
	/** @var MessageContents */
	private $messageContents;

	/**
	 * @return MessageContents|null
	 */
	public function getMessageContents(): ?MessageContents
	{
		return $this->messageContents;
	}

	/**
	 * @param MessageContents $messageContents
	 */
	public function setMessageContents(MessageContents $messageContents): void
	{
		$this->messageContents = $messageContents;
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

	protected function endpoint(): string
	{
		return 'send/transactional-emails-bulk';
	}
}
