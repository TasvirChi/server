<?php
/**
 * @package plugins.eventNotification
 * @subpackage api.objects
 */
class BorhanEventFieldCondition extends BorhanCondition
{	
	/**
	 * The field to be evaluated at runtime
	 * @var BorhanBooleanField
	 */
	public $field;

	private static $map_between_objects = array
	(
		'field' ,
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
			$dbObject = new kEventFieldCondition();
	
		return parent::toObject($dbObject, $skip);
	}
	 
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject()
	 */
	public function doFromObject($dbObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		/* @var $dbObject kEventFieldCondition */
		parent::doFromObject($dbObject, $responseProfile);
		
		$fieldType = get_class($dbObject->getField());
		BorhanLog::debug("Loading BorhanBooleanField from type [$fieldType]");
		switch ($fieldType)
		{
			case 'kEvalBooleanField':
				$this->field = new BorhanEvalBooleanField();
				break;
				
			default:
				$this->field = BorhanPluginManager::loadObject('BorhanBooleanField', $fieldType);
				break;
		}
		
		if($this->field)
			$this->field->fromObject($dbObject->getField());
	}
}
