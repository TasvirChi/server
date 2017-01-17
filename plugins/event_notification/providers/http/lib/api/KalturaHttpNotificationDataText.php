<?php
/**
 * @package plugins.httpNotification
 * @subpackage api.objects
 */
class BorhanHttpNotificationDataText extends BorhanHttpNotificationData
{
	/**
	 * @var BorhanStringValue
	 */
	public $content;
	
	/**
	 * It's protected on purpose, used by getData
	 * @see BorhanHttpNotificationDataText::getData()
	 * @var string
	 */
	protected $data;
	
	private static $map_between_objects = array
	(
		'content',
	);

	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbObject = null, $propertiesToSkip = array())
	{
		if(is_null($dbObject))
			$dbObject = new kHttpNotificationDataText();
			
		return parent::toObject($dbObject, $propertiesToSkip);
	}
	 
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject()
	 */
	public function doFromObject($dbObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		/* @var $dbObject kHttpNotificationDataText */
		parent::doFromObject($dbObject, $responseProfile);
		
		if($this->shouldGet('content', $responseProfile))
		{
			$contentType = get_class($dbObject->getContent());
			switch ($contentType)
			{
				case 'kStringValue':
					$this->content = new BorhanStringValue();
					break;
					
				case 'kEvalStringField':
					$this->content = new BorhanEvalStringField();
					break;
					
				default:
					$this->content = BorhanPluginManager::loadObject('BorhanStringValue', $contentType);
					break;
			}
			
			if($this->content)
				$this->content->fromObject($dbObject->getContent());
		}
			
		if($this->shouldGet('data', $responseProfile))
			$this->data = $dbObject->getData();
	}
	
	/* (non-PHPdoc)
	 * @see BorhanHttpNotificationData::getData()
	 */
	public function getData(kHttpNotificationDispatchJobData $jobData = null)
	{
		return $this->data;
	}
}
