<?php
/**
 * @package plugins.emailNotification
 * @subpackage api.objects
 */
class BorhanEmailNotificationRecipient extends BorhanObject
{
	/**
	 * Recipient e-mail address
	 * @var BorhanStringValue
	 */
	public $email;
	
	/**
	 * Recipient name
	 * @var BorhanStringValue
	 */
	public $name;
	
	private static $map_between_objects = array
	(
		'email',
		'name',
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
			$dbObject = new kEmailNotificationRecipient();
			
		return parent::toObject($dbObject, $skip);
	}
	 
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject()
	 */
	public function doFromObject($dbObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		/* @var $dbObject kEmailNotificationRecipient */
		parent::doFromObject($dbObject, $responseProfile);
		
		
		$emailType = get_class($dbObject->getEmail());
		switch ($emailType)
		{
			case 'kStringValue':
				$this->email = new BorhanStringValue();
				break;
				
			case 'kEvalStringField':
				$this->email = new BorhanEvalStringField();
				break;
				
			case 'kUserEmailContextField':
				$this->email = new BorhanUserEmailContextField();
				break;
				
			default:
				$this->email = BorhanPluginManager::loadObject('BorhanStringValue', $emailType);
				break;
		}
		if($this->email)
			$this->email->fromObject($dbObject->getEmail());
		
			
		$nameType = get_class($dbObject->getName());
		switch ($nameType)
		{
			case 'kStringValue':
				$this->name = new BorhanStringValue();
				break;
				
			case 'kEvalStringField':
				$this->name = new BorhanEvalStringField();
				break;
				
			default:
				$this->name = BorhanPluginManager::loadObject('BorhanStringValue', $nameType);
				break;
		}
		if($this->name)
			$this->name->fromObject($dbObject->getName());
	}
}
