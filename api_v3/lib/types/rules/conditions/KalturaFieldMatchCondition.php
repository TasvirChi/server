<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanFieldMatchCondition extends BorhanMatchCondition
{
	/**
	 * Field to evaluate
	 * @var BorhanStringField
	 */
	public $field;
	
	/**
	 * Init object type
	 */
	public function __construct() 
	{
		$this->type = ConditionType::FIELD_MATCH;
	}
	 
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbObject = null, $skip = array())
	{
		if(!$dbObject)
			$dbObject = new kFieldMatchCondition();
	
		/* @var $dbObject kFieldMatchCondition */
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
		BorhanLog::debug("Loading BorhanStringField from type [$fieldType]");
		switch ($fieldType)
		{
			case 'kCountryContextField':
				$this->field = new BorhanCountryContextField();
				break;
				
			case 'kIpAddressContextField':
				$this->field = new BorhanIpAddressContextField();
				break;
				
			case 'kUserAgentContextField':
				$this->field = new BorhanUserAgentContextField();
				break;
				
			case 'kUserEmailContextField':
				$this->field = new BorhanUserEmailContextField();
				break;
				
			case 'kCoordinatesContextField':
				$this->field = new BorhanCoordinatesContextField();
				break;

			case 'kEvalStringField':
			    $this->field = new BorhanEvalStringField();
			    break;
			
			case 'kObjectIdField':
			    $this->field = new BorhanObjectIdField();
			    break;				
				
			case 'kEvalStringField':
				$this->field = new BorhanEvalStringField();
				break;
				
			case 'kObjectIdField':
				$this->field = new BorhanObjectIdField();
				break;
				
			default:
				$this->field = BorhanPluginManager::loadObject('BorhanStringField', $fieldType);
				break;
		}
		
		if($this->field)
			$this->field->fromObject($dbObject->getField());
	}
}
