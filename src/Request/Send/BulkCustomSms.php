<?php declare(strict_types=1);

namespace SmartEmailing\v3\Request\Send;

use SmartEmailing\v3\Exceptions\PropertyRequiredException;

/**
 * Class BulkCustomSms
 *
 * @link https://app.smartemailing.cz/docs/api/v3/index.html#api-Custom_campaigns-Send_bulk_custom_SMS
 */
class BulkCustomSms extends AbstractSend
{
	/** @var int|null */
	protected $smsId;

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
