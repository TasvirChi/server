<?php
/**
 * @package plugins.eventNotification
 * @subpackage api.objects
 */
class BorhanEventNotificationDispatchJobData extends BorhanJobData
{
	/**
	 * @var int
	 */
	public $templateId;

	/**
	 * Define the content dynamic parameters
	 * @var BorhanKeyValueArray
	 */
	public $contentParameters;
	
	private static $map_between_objects = array
	(
		'templateId' ,
		'contentParameters',
	);

	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}
	
	/**
	 * @param string $subType is the provider type
	 * @return int
	 */
	public function toSubType($subType)
	{
		return kPluginableEnumsManager::apiToCore('EventNotificationTemplateType', $subType);
	}
	
	/**
	 * @param int $subType
	 * @return string
	 */
	public function fromSubType($subType)
	{
		return kPluginableEnumsManager::coreToApi('EventNotificationTemplateType', $subType);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject()
	 */
	protected function doFromObject($dbObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		/* @var $dbObject kEventNotificationDispatchJobData */
		parent::doFromObject($dbObject, $responseProfile);
		
		$this->contentParameters = BorhanKeyValueArray::fromKeyValueArray($dbObject->getContentParameters());
	}
}
