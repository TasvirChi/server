<?php
/**
 * @package plugins.eventNotification
 * @subpackage api.objects
 */
class BorhanEventNotificationTemplate extends BorhanObject implements IFilterable
{	
	/**
	 * @var int
	 * @readonly
	 * @filter eq,in,order
	 */
	public $id;
	
	/**
	 * @var int
	 * @readonly
	 * @filter eq,in
	 */
	public $partnerId;
	
	/**
	 * @var string
	 */
	public $name;
	
	/**
	 * @var string
	 * @filter eq,in
	 */
	public $systemName;
	
	/**
	 * @var string
	 */
	public $description;
	
	/**
	 * @var BorhanEventNotificationTemplateType
	 * @insertonly
	 * @filter eq,in
	 */
	public $type;
	
	/**
	 * @var BorhanEventNotificationTemplateStatus
	 * @readonly
	 * @filter eq,in
	 */
	public $status;
	
	/**
	 * @var time
	 * @readonly
	 * @filter gte,lte,order
	 */
	public $createdAt;

	/**
	 * @var time
	 * @readonly
	 * @filter gte,lte,order
	 */
	public $updatedAt;

	/**
	 * Define that the template could be dispatched manually from the API
	 * 
	 * @var bool
	 * @requiresPermission insert,update
	 */
	public $manualDispatchEnabled;

	/**
	 * Define that the template could be dispatched automatically by the system
	 * 
	 * @var bool
	 * @requiresPermission insert,update
	 */
	public $automaticDispatchEnabled;

	/**
	 * Define the event that should trigger this notification
	 * 
	 * @var BorhanEventNotificationEventType
	 * @requiresPermission update
	 */
	public $eventType;

	/**
	 * Define the object that raied the event that should trigger this notification
	 * 
	 * @var BorhanEventNotificationEventObjectType
	 * @requiresPermission update
	 */
	public $eventObjectType;

	/**
	 * Define the conditions that cause this notification to be triggered
	 * @var BorhanConditionArray
	 * @requiresPermission update
	 */
	public $eventConditions;
	
	/**
	 * Define the content dynamic parameters
	 * @var BorhanEventNotificationParameterArray
	 * @requiresPermission update
	 */
	public $contentParameters;
	
	/**
	 * Define the content dynamic parameters
	 * @var BorhanEventNotificationParameterArray
	 */
	public $userParameters;
	
	/**
	 * mapping between the field on this object (on the left) and the setter/getter on the entry object (on the right)  
	 */
	private static $map_between_objects = array(
		'id',
		'partnerId',
		'name',
		'systemName',
		'description',
		'status',
		'createdAt',
		'updatedAt',
		'manualDispatchEnabled',
		'automaticDispatchEnabled',
		'eventType',
		'eventObjectType' => 'objectType',
		'eventConditions',
		'contentParameters',
		'userParameters',
	);
		 
	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::validateForInsert()
	 */
	public function validateForInsert($propertiesToSkip = array())
	{
		$this->validatePropertyMinLength('name', 3, false);
		$this->validate();
		
		return parent::validateForInsert($propertiesToSkip);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::validateForUpdate()
	 */
	public function validateForUpdate($sourceObject, $propertiesToSkip = array())
	{
		$this->validatePropertyMinLength('name', 3, true);
		$this->validate($sourceObject);
		
		return parent::validateForUpdate($sourceObject, $propertiesToSkip);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbObject = null, $propertiesToSkip = array())
	{
		if(is_null($dbObject))
			throw new kCoreException("Event notification template type [" . $this->type . "] not found", kCoreException::OBJECT_TYPE_NOT_FOUND, $this->type);
        	
		return parent::toObject($dbObject, $propertiesToSkip);
	}
	
	/* (non-PHPdoc)
	 * @see IFilterable::getExtraFilters()
	 */
	public function getExtraFilters()
	{
		return array();
	}
	
	/* (non-PHPdoc)
	 * @see IFilterable::getFilterDocs()
	 */
	public function getFilterDocs()
	{
		return array();
	}
	
	/**
	 * @param int $type core enum value of EventNotificationTemplateType
	 * @return BorhanEventNotificationTemplate
	 */
	public static function getInstanceByType($type)
	{
		return BorhanPluginManager::loadObject('BorhanEventNotificationTemplate', $type);
	}
	
	protected function validate (EventNotificationTemplate $sourceObject = null)
	{
		$this->validatePropertyMinLength('systemName', 3, true);
		
		$id = null;
		if($sourceObject)
			$id = $sourceObject->getId();
			
		if(trim($this->systemName) && !$this->isNull('systemName'))
		{
			$systemNameTemplates = EventNotificationTemplatePeer::retrieveBySystemName($this->systemName, $id);
	        if (count($systemNameTemplates))
	            throw new BorhanAPIException(BorhanEventNotificationErrors::EVENT_NOTIFICATION_TEMPLATE_DUPLICATE_SYSTEM_NAME, $this->systemName);
		}
	}
}