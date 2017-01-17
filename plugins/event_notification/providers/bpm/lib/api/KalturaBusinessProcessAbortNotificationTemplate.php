<?php
/**
 * @package plugins.businessProcessNotification
 * @subpackage api.objects
 */
class BorhanBusinessProcessAbortNotificationTemplate extends BorhanBusinessProcessNotificationTemplate
{	
	public function __construct()
	{
		$this->type = BusinessProcessNotificationPlugin::getApiValue(BusinessProcessNotificationTemplateType::BPM_ABORT);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbObject = null, $propertiesToSkip = array())
	{
		if(is_null($dbObject))
			$dbObject = new BusinessProcessAbortNotificationTemplate();
			
		return parent::toObject($dbObject, $propertiesToSkip);
	}
}