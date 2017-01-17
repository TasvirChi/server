<?php
/**
 * Abstract engine which retrieves a list of the email notification recipients.
 * 
 * @package plugins.emailNotification
 * @subpackage Scheduler
 */
abstract class KEmailNotificationRecipientEngine
{
	/**
	 * Job data for the email notification recipients
	 * @var BorhanEmailNotificationRecipientJobData
	 */
	protected $recipientJobData;
	
	public function __construct(BorhanEmailNotificationRecipientJobData $recipientJobData)
	{
		$this->recipientJobData = $recipientJobData;
		
	}
	
	/**
	 * Function retrieves instance of recipient job data
	 * @param BorhanEmailNotificationRecipientJobData $recipientJobData
	 * @param BorhanClient $kClient
	 * @return KEmailNotificationRecipientEngine
	 */
	public static function getEmailNotificationRecipientEngine(BorhanEmailNotificationRecipientJobData $recipientJobData)
	{
		return BorhanPluginManager::loadObject('KEmailNotificationRecipientEngine', $recipientJobData->providerType, array($recipientJobData));
	}

	
	/**
	 * Function returns an array of the recipients who should receive the email notification regarding the category
	 * @param array $contentParameters
	 */
	abstract function getRecipients (array $contentParameters);
}