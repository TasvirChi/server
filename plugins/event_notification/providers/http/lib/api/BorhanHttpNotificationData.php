<?php
/**
 * @package plugins.httpNotification
 * @subpackage api.objects
 * @abstract
 */
abstract class BorhanHttpNotificationData extends BorhanObject
{
	/**
	 * @param kHttpNotificationData $coreObject
	 * @return BorhanHttpNotificationData
	 */
	public static function getInstance(kHttpNotificationData $coreObject)
	{
		$dataType = get_class($coreObject);
		$data = null;
		switch ($dataType)
		{
			case 'kHttpNotificationDataFields':
				$data = new BorhanHttpNotificationDataFields();
				break;
				
			case 'kHttpNotificationDataText':
				$data = new BorhanHttpNotificationDataText();
				break;
				
			case 'kHttpNotificationObjectData':
				$data = new BorhanHttpNotificationObjectData();
				break;
				
			default:
				$data = BorhanPluginManager::loadObject('BorhanHttpNotificationData', $dataType);
				break;
		}
		
		if($data)
			$data->fromObject($coreObject);
			
		return $data;
	}

	/**
	 * @param $jobData kHttpNotificationDispatchJobData
	 * @return string the data to be sent
	 */
	abstract public function getData(kHttpNotificationDispatchJobData $jobData = null);
}
