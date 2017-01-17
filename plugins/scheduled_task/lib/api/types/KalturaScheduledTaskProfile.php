<?php

/**
 * @package plugins.scheduledTask
 * @subpackage api.objects
 */
class BorhanScheduledTaskProfile extends BorhanObject implements IFilterable
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
	 * @var BorhanScheduledTaskProfileStatus
	 * @filter eq,in
	 */
	public $status;

	/**
	 * The type of engine to use to list objects using the given "objectFilter"
	 *
	 * @var BorhanObjectFilterEngineType
	 */
	public $objectFilterEngineType;

	/**
	 * A filter object (inherits BorhanFilter) that is used to list objects for scheduled tasks
	 *
	 * @var BorhanFilter
	 */
	public $objectFilter;

	/**
	 * A list of tasks to execute on the founded objects
	 *
	 * @var BorhanObjectTaskArray
	 */
	public $objectTasks;
	
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
	 * @var time
	 * @filter gte,lte,order
	 */
	public $lastExecutionStartedAt;

	/**
	 * The maximum number of result count allowed to be processed by this profile per execution
	 *
	 * @var int
	 */
	public $maxTotalCountAllowed;

	/*
	 */
	private static $map_between_objects = array(
		'id',
		'partnerId',
		'name',
		'systemName',
		'description',
		'status',
		'objectFilterEngineType',
		'objectFilter',
		'objectTasks',
		'createdAt',
		'updatedAt',
		'lastExecutionStartedAt',
		'maxTotalCountAllowed',
	);
		 
	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}

	public function toInsertableObject($objectToFill = null, $propertiesToSkip = array())
	{
		if (is_null($this->status))
			$this->status = BorhanScheduledTaskProfileStatus::DISABLED;

		return parent::toInsertableObject($objectToFill, $propertiesToSkip);
	}

	/* (non-PHPdoc)
	 * @see BorhanObject::validateForInsert()
	 */
	public function validateForInsert($propertiesToSkip = array())
	{
		$this->validatePropertyMinLength('name', 3, false);
		$this->validatePropertyMinLength('systemName', 3, true);
		$this->validatePropertyNotNull('objectFilterEngineType');
		$this->validatePropertyNotNull('objectFilter');
		$this->validatePropertyNotNull('objectTasks');
		$this->validatePropertyNotNull('maxTotalCountAllowed');
		foreach($this->objectTasks as $objectTask)
		{
			/* @var BorhanObjectTask $objectTask */
			$objectTask->validateForInsert(array('type'));
		}
		parent::validateForInsert($propertiesToSkip);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::validateForUpdate()
	 */
	public function validateForUpdate($sourceObject, $propertiesToSkip = array())
	{
		$this->validatePropertyMinLength('name', 3, true);
		$this->validatePropertyMinLength('systemName', 3, true);

		return parent::validateForUpdate($sourceObject, $propertiesToSkip);
	}

	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbObject = null, $propertiesToSkip = array())
	{
		if(is_null($dbObject))
			$dbObject = new ScheduledTaskProfile();

		$dbObject = parent::toObject($dbObject, $propertiesToSkip);
		if (!is_null($this->objectFilter))
			$dbObject->setObjectFilterApiType(get_class($this->objectFilter));
		return $dbObject;
	}

	/**
	 * @param ScheduledTaskProfile $srcObj
	 */
	public function doFromObject($srcObj, BorhanDetachedResponseProfile $responseProfile = null)
	{
		parent::doFromObject($srcObj, $responseProfile);
		$this->objectTasks = BorhanObjectTaskArray::fromDbArray($srcObj->getObjectTasks());
		$filterType = $srcObj->getObjectFilterApiType();
		if (!class_exists($filterType))
		{
			BorhanLog::err(sprintf('Class %s not found, cannot initiate object filter instance', $filterType));
			$this->objectFilter = new BorhanFilter();
		}
		else
		{
			$this->objectFilter = new $filterType();
		}

		$this->objectFilter->fromObject($srcObj->getObjectFilter());
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
}