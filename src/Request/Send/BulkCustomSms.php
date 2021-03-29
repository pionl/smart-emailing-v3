<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Send;

use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Request\Response;

/**
 * Class BulkCustomSms
 *
 * @link https://app.smartemailing.cz/docs/api/v3/index.html#api-Custom_campaigns-Send_bulk_custom_SMS
 */
class BulkCustomSms extends AbstractSend
{
	/**
	 * The maximum tasks per single request
	 * Single request is restricted to contain 500 SMS at most. Multiple simultaneous calls is allowed.
	 * @var int
	 */
	protected $chunkLimit = 500;

	/** @var int|null */
	protected $smsId;

	/**
	 * Will send multiple requests because of the 500 count limit
	 * @inheritDoc
	 */
	public function send(): ?Response
	{
		// There is not enough contacts to enable chunk mode
		if ($this->chunkLimit >= count($this->getTasks())) {
			return parent::send();
		}

		return $this->sendInChunkMode();
	}

	/**
	 * Sends tasks in chunk mode
	 * @return Response
	 */
	protected function sendInChunkMode(): ?Response
	{
		$originalFullTaskList = $this->getTasks();
		$lastResponse = null;

		foreach (array_chunk($this->getTasks(), $this->chunkLimit) as $tasks) {
			$this->tasks = $tasks;

			$lastResponse = parent::send();
		}

		$this->tasks = $originalFullTaskList;

		return $lastResponse;
	}

	/**
	 * @return int|null
	 */
	public function getSmsId(): ?int
	{
		return $this->smsId;
	}

	/**
	 * @param int|null $smsId
	 */
	public function setSmsId(int $smsId)
	{
		$this->smsId = $smsId;
	}

	/**
	 * Converts data to array
	 *
	 * @return array
	 */
	public function toArray(): array
	{
		foreach ($this->getTasks() as $task) {
			PropertyRequiredException::throwIf(
				'cellphone',
				!is_null($task->getRecipient()) && !empty($task->getRecipient()->getCellphone()),
				'You must set cellphone for recipient - missing cellphone'
			);
		}
		return [
			'tag' => $this->getTag(),
			'sms_id' => $this->getSmsId(),
			'tasks' => $this->getTasks(),
		];
	}

	/**
	 * @return string
	 */
	protected function endpoint()
	{
		return 'send/custom-sms-bulk';
	}
}
