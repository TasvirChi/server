<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanFieldCompareCondition extends BorhanCompareCondition
{
	/**
	 * Field to evaluate
	 * @var BorhanIntegerField
	 */
	public $field;
	 
	/**
	 * Init object type
	 */
	public function __construct() 
	{
		$this->type = ConditionType::FIELD_COMPARE;
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbObject = null, $skip = array())
	{
		if(!$dbObject)
			$dbObject = new kFieldCompareCondition();
	
		/* @var $dbObject kFieldCompareCondition */
		$dbObject->setField($this->field->toObject());
			
		return parent::toObject($dbObject, $skip);
	}
	 
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject()
	 */
	public function doFromObject($dbObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		/* @var $dbObject kFieldMatchCondition */
		parent::doFromObject($dbObject, $responseProfile);
		
		$fieldType = get_class($dbObject->getField());
		BorhanLog::debug("Loading BorhanIntegerField from type [$fieldType]");
		switch ($fieldType)
		{
			case 'kTimeContextField':
				$this->field = new BorhanTimeContextField();
				break;
				
			default:
				$this->field = BorhanPluginManager::loadObject('BorhanIntegerField', $fieldType);
				break;
		}
		
		if($this->field)
			$this->field->fromObject($dbObject->getField());
	}
}
