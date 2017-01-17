<?php
/**
 * @package api
 * @subpackage objects
 * @deprecated use BorhanAccessControlProfile instead
 */
class BorhanAccessControl extends BorhanObject implements IRelatedFilterable 
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
	 * Creation date as Unix timestamp (In seconds) 
	 * 
	 * @var time
	 * @readonly
	 * @filter gte,lte,order
	 */
	public $createdAt;
	
	/**
	 * True if this Conversion Profile is the default
	 *  
	 * @var BorhanNullableBoolean
	 */
	public $isDefault;
	
	/**
	 * Array of Access Control Restrictions
	 * 
	 * @var BorhanRestrictionArray
	 */
	public $restrictions;
	
	/**
	 * Indicates that the access control profile is new and should be handled using BorhanAccessControlProfile object and accessControlProfile service
	 * 
	 * @var bool
	 * @readonly
	 */
	public $containsUnsuportedRestrictions;
	
	private static $mapBetweenObjects = array
	(
		"id",
		"name",
		"systemName",
		"partnerId",
		"description",
		"createdAt",
		"isDefault",
	);
	
	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbObject = null, $skip = array())
	{
		if(!$dbObject)
			$dbObject = new accessControl();
			
		/* @var $dbObject accessControl */
		parent::toObject($dbObject);
		
		if ($this->restrictions instanceof BorhanRestrictionArray)
		{
			$rules = array();
			foreach($this->restrictions as $restriction)
			{
				/* @var $restriction BorhanBaseRestriction */
				$restrictions = clone $this->restrictions;
				$rule = $restriction->toRule($this->restrictions);
				if($rule)
					$rules[] = $rule;
			}
				
			$dbObject->setRulesArray($rules);
		}
		
		return $dbObject;
	}

	/* (non-PHPdoc)
	 * @see BorhanObject::validateForInsert()
	 */
	public function validateForInsert($propertiesToSkip = array())
	{
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
	 * @see BorhanObject::toUpdatableObject()
	 */
	public function toUpdatableObject($dbObject, $skip = array())
	{
		/* @var $dbObject accessControl */
		$rules = $dbObject->getRulesArray();
		foreach($rules as $rule)
			if(!($rule instanceof kAccessControlRestriction))
				throw new BorhanAPIException(BorhanErrors::ACCESS_CONTROL_NEW_VERSION_UPDATE, $dbObject->getId());
		
		parent::toUpdatableObject($dbObject, $skip);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject()
	 */
	public function doFromObject($dbObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		parent::doFromObject($dbObject, $responseProfile);
		
		if (!($dbObject instanceof accessControl))
			return;
			
		if($this->shouldGet('restrictions', $responseProfile))
		{
			$rules = $dbObject->getRulesArray();
			foreach($rules as $rule)
			{
				if(!($rule instanceof kAccessControlRestriction))
				{
					BorhanLog::info("Access control [" . $dbObject->getId() . "] rules are new and cannot be loaded using old object");
					$this->containsUnsuportedRestrictions = true;
					return;
				}
			}
			$this->restrictions = BorhanRestrictionArray::fromDbArray($rules);
		}
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