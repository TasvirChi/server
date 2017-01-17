<?php
/**
 * Evaluates PHP statement, depends on the execution context
 * 
 * @package plugins.httpNotification
 * @subpackage api.objects
 */
class BorhanHttpNotificationObjectData extends BorhanHttpNotificationData
{
	/**
	 * Borhan API object type
	 * @var string
	 */
	public $apiObjectType;
	
	/**
	 * Data format
	 * @var BorhanResponseType
	 */
	public $format;
	
	/**
	 * Ignore null attributes during serialization
	 * @var bool
	 */
	public $ignoreNull;
	
	/**
	 * PHP code
	 * @var string
	 */
	public $code;
	
	/**
	 * Serialized object, protected on purpose, used by getData
	 * @see BorhanHttpNotificationObjectData::getData()
	 * @var string
	 */
	protected $coreObject;

	static private $map_between_objects = array
	(
		'apiObjectType' => 'objectType',
		'format',
		'ignoreNull',
		'code',
	);

	/* (non-PHPdoc)
	 * @see BorhanValue::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbObject = null, $skip = array())
	{
		if(!$this->apiObjectType || !is_subclass_of($this->apiObjectType, 'BorhanObject'))
			throw new BorhanAPIException(BorhanHttpNotificationErrors::HTTP_NOTIFICATION_INVALID_OBJECT_TYPE);
			
		if(!$dbObject)
			$dbObject = new kHttpNotificationObjectData();
			
		return parent::toObject($dbObject, $skip);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject($srcObj)
	 */
	public function doFromObject($srcObj, BorhanDetachedResponseProfile $responseProfile = null)
	{
		/* @var $srcObj kHttpNotificationObjectData */
		parent::doFromObject($srcObj, $responseProfile);
		$this->coreObject = $srcObj->getCoreObject();
	}
	
	/* (non-PHPdoc)
	 * @see BorhanHttpNotificationData::getData()
	 */
	public function getData(kHttpNotificationDispatchJobData $jobData = null)
	{
		$coreObject = unserialize($this->coreObject);
		$apiObject = new $this->apiObjectType;
		/* @var $apiObject BorhanObject */
		$apiObject->fromObject($coreObject);
		
		$httpNotificationTemplate = EventNotificationTemplatePeer::retrieveByPK($jobData->getTemplateId());
		
		$notification = new BorhanHttpNotification();
		$notification->object = $apiObject;
		$notification->eventObjectType = kPluginableEnumsManager::coreToApi('EventNotificationEventObjectType', $httpNotificationTemplate->getObjectType());
		$notification->eventNotificationJobId = $jobData->getJobId();
		$notification->templateId = $httpNotificationTemplate->getId();
		$notification->templateName = $httpNotificationTemplate->getName();
		$notification->templateSystemName = $httpNotificationTemplate->getSystemName();
		$notification->eventType = $httpNotificationTemplate->getEventType();;

		$data = '';
		switch ($this->format)
		{
			case BorhanResponseType::RESPONSE_TYPE_XML:
				$serializer = new BorhanXmlSerializer($this->ignoreNull);				
				$data = '<notification>' . $serializer->serialize($notification) . '</notification>';
				break;
				
			case BorhanResponseType::RESPONSE_TYPE_PHP:
				$serializer = new BorhanPhpSerializer($this->ignoreNull);				
				$data = $serializer->serialize($notification);
				break;
				
			case BorhanResponseType::RESPONSE_TYPE_JSON:
				$serializer = new BorhanJsonSerializer($this->ignoreNull);				
				$data = $serializer->serialize($notification);
				if (!$httpNotificationTemplate->getUrlEncode())
					return $data;
				
				$data = urlencode($data);
				break;
		}
		
		return "data=$data";
	}
}