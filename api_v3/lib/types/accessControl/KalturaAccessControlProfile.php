<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanAccessControlProfile extends BorhanObject implements IRelatedFilterable 
{
	/**
	 * The id of the Access Control Profile
	 * 
	 * @var int
	 * @readonly
	 * @filter eq,in
	 */
	public $id;
	
	/**
	 * @var int
	 * @readonly
	 */
	public $partnerId;
	
	/**
	 * The name of the Access Control Profile
	 * 
	 * @var string
	 */
	public $name;
	
	/**
	 * System name of the Access Control Profile
	 * 
	 * @var string
	 * @filter eq,in
	 */
	public $systemName;
	
	/**
	 * The description of the Access Control Profile
	 * 
	 * @var string
	 */
	public $description;
	
	/**
	 * Creation time as Unix timestamp (In seconds) 
	 * 
	 * @var time
	 * @readonly
	 * @filter gte,lte,order
	 */
	public $createdAt;
	
	/**
	 * Update time as Unix timestamp (In seconds) 
	 * 
	 * @var time
	 * @readonly
	 * @filter gte,lte,order
	 */
	public $updatedAt;
	
	/**
	 * True if this access control profile is the partner default
	 *  
	 * @var BorhanNullableBoolean
	 */
	public $isDefault;
	
	/**
	 * Array of access control rules
	 * 
	 * @var BorhanRuleArray
	 */
	public $rules;
	
	private static $mapBetweenObjects = array
	(
		"id",
		"name",
		"systemName",
		"partnerId",
		"description",
		"createdAt",
		"updatedAt",
		"isDefault",
		"rules" => "rulesArray",
	);
	
	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
	}
	
	/**
	 * Common validation to insert and update
	 */
	public function validate()
	{
		$this->validatePropertyMaxLength('systemName', 128, true);
		$this->validatePropertyMaxLength('description', 1024, true);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::validateForInsert()
	 */
	public function validateForInsert($propertiesToSkip = array())
	{
		$this->validatePropertyMinMaxLength('name', 1, 128);
		$this->validate();
		
		if($this->systemName)
		{
			$c = BorhanCriteria::create(accessControlPeer::OM_CLASS);
			$c->add(accessControlPeer::SYSTEM_NAME, $this->systemName);
			if(accessControlPeer::doCount($c))
				throw new BorhanAPIException(BorhanErrors::SYSTEM_NAME_ALREADY_EXISTS, $this->systemName);
		}
		
		return parent::validateForInsert($propertiesToSkip);
	}

	/* (non-PHPdoc)
	 * @see BorhanObject::validateForUpdate()
	 */
	public function validateForUpdate($sourceObject, $propertiesToSkip = array())
	{
		/* @var $sourceObject accessControl */
		
		$this->validatePropertyMinMaxLength('name', 1, 128, true);
		$this->validate();
		
		if($this->systemName)
		{
			$c = BorhanCriteria::create(accessControlPeer::OM_CLASS);
			$c->add(accessControlPeer::ID, $sourceObject->getId(), Criteria::NOT_EQUAL);
			$c->add(accessControlPeer::SYSTEM_NAME, $this->systemName);
			if(accessControlPeer::doCount($c))
				throw new BorhanAPIException(BorhanErrors::SYSTEM_NAME_ALREADY_EXISTS, $this->systemName);
		}
		
		return parent::validateForUpdate($sourceObject, $propertiesToSkip);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbAccessControlProfile = null, $skip = array())
	{
		if(!$dbAccessControlProfile)
			$dbAccessControlProfile = new accessControl();
			
		return parent::toObject($dbAccessControlProfile, $skip);
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