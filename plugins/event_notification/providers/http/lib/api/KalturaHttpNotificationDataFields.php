<?php
/**
 * If this class used as the template data, the fields will be taken from the content parameters
 * @package plugins.httpNotification
 * @subpackage api.objects
 */
class BorhanHttpNotificationDataFields extends BorhanHttpNotificationData
{
	/**
	 * It's protected on purpose, used by getData
	 * @see BorhanHttpNotificationDataFields::getData()
	 * @var string
	 */
	protected $data;
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbObject = null, $propertiesToSkip = array())
	{
		if(is_null($dbObject))
			$dbObject = new kHttpNotificationDataFields();
			
		return parent::toObject($dbObject, $propertiesToSkip);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject($srcObj)
	 */
	public function doFromObject($srcObj, BorhanDetachedResponseProfile $responseProfile = null)
	{
		/* @var $srcObj kHttpNotificationDataFields */
		parent::doFromObject($srcObj, $responseProfile);
		
		if($this->shouldGet('data', $responseProfile))
			$this->data = $srcObj->getData();
	}
	
	/* (non-PHPdoc)
	 * @see BorhanHttpNotificationData::getData()
	 */
	public function getData(kHttpNotificationDispatchJobData $jobData = null)
	{
		return $this->data;
	}
}
