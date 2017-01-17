<?php
/**
 * @package plugins.eventNotification
 * @subpackage api.objects
 */
class BorhanEventNotificationParameter extends BorhanObject
{
	/**
	 * The key in the subject and body to be replaced with the dynamic value
	 * @var string
	 */
	public $key;

	/**
	 * @var string
	 */
	public $description;
	
	/**
	 * The dynamic value to be placed in the final output
	 * @var BorhanStringValue
	 */
	public $value;
	
	private static $map_between_objects = array
	(
		'key',
		'description',
		'value',
	);

	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbObject = null, $skip = array())
	{
		if(!$dbObject)
			$dbObject = new kEventNotificationParameter();
			
		return parent::toObject($dbObject, $skip);
	}
	 
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject()
	 */
	public function doFromObject($dbObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		/* @var $dbObject kEventValueCondition */
		parent::doFromObject($dbObject, $responseProfile);
		
		$valueType = get_class($dbObject->getValue());
		BorhanLog::debug("Loading BorhanStringValue from type [$valueType]");
		switch ($valueType)
		{
			case 'kMetadataField':
				$this->value = new BorhanMetadataField();
				break;
				
			case 'kStringValue':
				$this->value = new BorhanStringValue();
				break;
				
			case 'kEvalStringField':
				$this->value = new BorhanEvalStringField();
				break;
				
			default:
				$this->value = BorhanPluginManager::loadObject('BorhanStringValue', $valueType);
				break;
		}
		
		if($this->value)
			$this->value->fromObject($dbObject->getValue());
	}
}