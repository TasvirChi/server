<?php
/**
 * @package plugins.businessProcessNotification
 * @subpackage api.objects
 */
abstract class BorhanBusinessProcessServer extends BorhanObject implements IFilterable
{	
	/**
	 * Auto generated identifier
	 * @var int
	 * @readonly
	 * @filter eq,in,notin
	 */
	public $id;

	/**
	 * Server creation date as Unix timestamp (In seconds)
	 * 
	 * @var time
	 * @readonly
	 * @filter gte,lte,order
	 */
	public $createdAt;

	/**
	 * Server update date as Unix timestamp (In seconds)
	 * 
	 * @var time
	 * @readonly
	 * @filter gte,lte,order
	 */
	public $updatedAt;

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
	 */
	public $systemName;

	/**
	 * @var string
	 */
	public $description;

	/**
	 * @var BorhanBusinessProcessServerStatus
	 * @readonly
	 * @filter eq,not,in,notin
	 */
	public $status;

	/**
	 * The type of the server, this is auto filled by the derived server object
	 * @var BorhanBusinessProcessProvider
	 * @readonly
	 * @filter eq,in
	 */
	public $type;
	
	/**
	 * The dc of the server
	 * @var int
	 */
	public $dc;

	/**
	 * mapping between the field on this object (on the left) and the setter/getter on the entry object (on the right)  
	 */
	private static $map_between_objects = array(
		'id',
		'createdAt',
		'updatedAt',
		'partnerId',
		'name',
		'systemName',
		'description',
		'status',
		'type',
		'dc',
	);
		 
	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
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
	
	/* (non-PHPdoc)
	 * @see BorhanObject::validateForInsert()
	 */
	public function validateForInsert($propertiesToSkip = array())
	{
		$this->validatePropertyNotNull('type');
		$propertiesToSkip[] = 'type';
		return parent::validateForInsert($propertiesToSkip);
	}
		
	/* (non-PHPdoc)
	 * @see BorhanObject::validateForUpdate()
	 */
	public function validateForUpdate($sourceObject, $propertiesToSkip = array())
	{
		$propertiesToSkip[] = 'type';
		return parent::validateForUpdate($sourceObject, $propertiesToSkip);
	}
	
	/**
	 * @param int $type core enum value of BusinessProcessProvider
	 * @return BorhanBusinessProcessServer
	 */
	public static function getInstanceByType($type)
	{
		return BorhanPluginManager::loadObject('BorhanBusinessProcessServer', $type);
	}
}