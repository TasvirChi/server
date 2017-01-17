<?php
/**
 * Abstract class representing the final output recipients going into the batch mechanism
 * @package plugins.emailNotification
 * @subpackage model.data
 */
abstract class BorhanEmailNotificationRecipientJobData extends BorhanObject
{
	 /**
	  * Provider type of the job data.
	  * @var BorhanEmailNotificationRecipientProviderType
	  * 
	  * @readonly
	  */
	 public $providerType;
	 
	/**
	 * Protected setter to set the provider type of the job data
	 */
	abstract protected function setProviderType ();
	
	/**
	 * Function returns correct API recipient data type based on the DB class received.
	 * @param kEmailNotificationRecipientJobData $dbData
	 * @return Borhan
	 */
	public static function getDataInstance ($dbData)
	{
		$instance = null;
		if ($dbData)
		{
			switch (get_class($dbData))
			{
				case 'kEmailNotificationCategoryRecipientJobData':
					$instance = new BorhanEmailNotificationCategoryRecipientJobData();
					break;
				case 'kEmailNotificationStaticRecipientJobData':
					$instance = new BorhanEmailNotificationStaticRecipientJobData();
					break;
				case 'kEmailNotificationUserRecipientJobData':
					$instance = new BorhanEmailNotificationUserRecipientJobData();
					break;
				default:
					$instance = BorhanPluginManager::loadObject('BorhanEmailNotificationRecipientJobData', $dbData->getProviderType());
					break;
			}
			
			if ($instance)
				$instance->fromObject($dbData);
		}
			
		return $instance;
		
	}
}