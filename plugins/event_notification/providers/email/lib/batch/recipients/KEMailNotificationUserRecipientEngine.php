<?php
/**
 * Engine which retrieves a dynamic list of user recipients based on provided filter
 * 
 * @package plugins.emailNotification
 * @subpackage Scheduler
 */
class KEmailNotificationUserRecipientEngine extends  KEmailNotificationRecipientEngine
{
	/* (non-PHPdoc)
	 * @see KEmailNotificationRecipientEngine::getRecipients()
	 */
	function getRecipients(array $contentParameters) {
	    
               $pager = new BorhanFilterPager();
               $pager->pageSize = 500;
		//list users
		$userList = KBatchBase::$kClient->user->listAction($this->recipientJobData->filter, $pager);
		
		$recipients = array();
		foreach ($userList->objects as $user)
		{
			/* @var $user BorhanUser */
			$recipients[$user->email] = $user->firstName. ' ' . $user->lastName;
		}
		
		return $recipients;
	}

	
}