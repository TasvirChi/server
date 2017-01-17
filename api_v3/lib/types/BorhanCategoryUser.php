<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanCategoryUser extends BorhanObject implements IRelatedFilterable
{
	/**
	 * 
	 * @var int
	 * @insertonly
	 * @filter eq,in
	 */
	public $categoryId;
	
	/**
	 * User id
	 * 
	 * @var string
	 * @insertonly
	 * @filter eq,in
	 */
	public $userId;
	
	/**
	 * Partner id
	 * 
	 * @var int
	 * @readonly
	 */
	public $partnerId;
	
	/**
	 * Permission level
	 * @deprecated
	 * @var BorhanCategoryUserPermissionLevel
	 * @filter eq,in
	 */
	public $permissionLevel;
	
	/**
	 * Status
	 * 
	 * @var BorhanCategoryUserStatus
	 * @readonly
	 * @filter eq,in
	 */
	public $status;
	
	/**
	 * CategoryUser creation date as Unix timestamp (In seconds)
	 * 
	 * @var time
	 * @readonly
	 * @filter gte,lte,order
	 */
	public $createdAt;
	
	/**
	 * CategoryUser update date as Unix timestamp (In seconds)
	 * 
	 * @var time
	 * @readonly
	 * @filter gte,lte,order
	 */
	public $updatedAt;
	
	/**
	 * Update method can be either manual or automatic to distinguish between manual operations (for example in BMC) on automatic - using bulk upload 
	 * 
	 * @var BorhanUpdateMethodType
	 * @filter eq, in
	 */
	public $updateMethod;
	
	/**
	 * The full ids of the Category
	 * 
	 * @var string
	 * @readonly
	 * @filter likex,eq
	 */
	public $categoryFullIds;
	
	/**
	 * Set of category-related permissions for the current category user.
	 * @var string
	 * @filter matchand,matchor,notcontains
	 */
	public $permissionNames;
	
	private static $mapBetweenObjects = array
	(
		"categoryId",
		"userId" => "puserId",
		"partnerId",
		"permissionLevel",
		"status",
		"createdAt",
		"updatedAt",
		"updateMethod",
		"categoryFullIds",
		"permissionNames",
	);
	
	public function toObject($dbObject = null, $skip = array()) {
	    
		if (is_null ( $dbObject ))
			$dbObject = new categoryKuser ();
		/* @var $dbObject categoryKuser */
		if (!$this->permissionNames && !is_null($this->permissionLevel) && $this->permissionLevel !== $dbObject->getPermissionLevel())
		{
			$permissionNames = $dbObject->getPermissionNames();
			if ($permissionNames)
			{
				$permissionNamesArr = explode(',', $permissionNames);
				$permissionNamesArr = categoryKuser::removeCategoryPermissions($permissionNamesArr);
			}
			else 
			{
				$permissionNamesArr = array();
			}
			
			$permissionNamesArr = categoryKuser::getPermissionNamesByPermissionLevel($this->permissionLevel);
			
			$dbObject->setPermissionNames(implode(',', $permissionNamesArr));
		}
		parent::toObject ( $dbObject, $skip );
		
		return $dbObject;
	}
	
	
	/*
	 * mapping between the field on this object (on the left) and the setter/getter on the CategoryKuser object (on the right)  
	 */
	public function getMapBetweenObjects() {
		return array_merge ( parent::getMapBetweenObjects (), self::$mapBetweenObjects );
	}
	
	public function getExtraFilters() {
		return array ();
	}
	
	public function getFilterDocs() {
		return array ();
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::validateForInsert()
	 */
	public function validateForInsert($propertiesToSkip = array()) 
	{
		$category = categoryPeer::retrieveByPK ( $this->categoryId );
		if (! $category)
			throw new BorhanAPIException ( BorhanErrors::CATEGORY_NOT_FOUND, $this->categoryId );
		
		if ($category->getInheritanceType () == InheritanceType::INHERIT)
			throw new BorhanAPIException ( BorhanErrors::CATEGORY_INHERIT_MEMBERS, $this->categoryId );
		
		//validating userId is not 0 or null
		if($this->userId == "0")
		    throw new BorhanAPIException ( BorhanErrors::INVALID_USER_ID);
		$this->validatePropertyMinLength('userId',1);
		
		$partnerId = kCurrentContext::$partner_id ? kCurrentContext::$partner_id : kCurrentContext::$ks_partner_id;
		
		$kuser = kuserPeer::getKuserByPartnerAndUid ($partnerId , $this->userId );
		if($kuser)
		{
			$categoryKuser = categoryKuserPeer::retrieveByCategoryIdAndKuserId ( $this->categoryId, $kuser->getId () );
			if ($categoryKuser)
				throw new BorhanAPIException ( BorhanErrors::CATEGORY_USER_ALREADY_EXISTS );
		}
		
		$currentKuserCategoryKuser = categoryKuserPeer::retrievePermittedKuserInCategory($this->categoryId, kCurrentContext::getCurrentKsKuserId());
		if ((! $currentKuserCategoryKuser || 
				$currentKuserCategoryKuser->getPermissionLevel () != CategoryKuserPermissionLevel::MANAGER) && 
				$category->getUserJoinPolicy () == UserJoinPolicyType::NOT_ALLOWED && 
				kEntitlementUtils::getEntitlementEnforcement ()) {
			throw new BorhanAPIException ( BorhanErrors::CATEGORY_USER_JOIN_NOT_ALLOWED, $this->categoryId );
		}
		
		//if user doesn't exists - create it
		if(!$kuser)
		{
			if(!preg_match(kuser::PUSER_ID_REGEXP, $this->userId))
				throw new BorhanAPIException(BorhanErrors::INVALID_FIELD_VALUE, 'userId');
				
			kuserPeer::createKuserForPartner($partnerId, $this->userId);
		}
		
		return parent::validateForInsert ( $propertiesToSkip );
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toInsertableObject()
	 */
	public function toInsertableObject($dbObject = null, $skip = array())
	{
	    if (is_null($this->permissionLevel))
	    {
    	    $category = categoryPeer::retrieveByPK($this->categoryId);
    	    if(!$category)
    	    	throw new BorhanAPIException ( BorhanErrors::CATEGORY_NOT_FOUND, $this->categoryId );
    	    
	        $this->permissionLevel = $category->getDefaultPermissionLevel();
	    }
	    
	    return parent::toInsertableObject($dbObject, $skip);
	}

	/* (non-PHPdoc)
	 * @see BorhanObject::validateForUpdate($sourceObject, $propertiesToSkip)
	 */
	public function validateForUpdate($sourceObject, $propertiesToSkip = null)
	{
		/* @var $sourceObject categoryKuser */
		$category = categoryPeer::retrieveByPK($sourceObject->getCategoryId());
		if (!$category)
			throw new BorhanAPIException(BorhanErrors::CATEGORY_NOT_FOUND, $sourceObject->getCategoryId());
			
		if ($this->permissionNames && $this->permissionNames != $sourceObject->getPermissionNames())
		{
			if($sourceObject->getKuserId() == $category->getKuserId())
			{
				if (strpos($this->permissionNames, PermissionName::CATEGORY_EDIT) === false)
				{
					throw new BorhanAPIException(BorhanErrors::CANNOT_UPDATE_CATEGORY_USER_OWNER);
				}
			}
		}
		
		$currentKuserCategoryKuser = categoryKuserPeer::retrievePermittedKuserInCategory($sourceObject->getCategoryId(), kCurrentContext::getCurrentKsKuserId());
		if(kEntitlementUtils::getEntitlementEnforcement() && 
		(!$currentKuserCategoryKuser || !$currentKuserCategoryKuser->hasPermission(PermissionName::CATEGORY_EDIT)))
			throw new BorhanAPIException(BorhanErrors::CANNOT_UPDATE_CATEGORY_USER, $sourceObject->getCategoryId());
			
		return parent::validateForUpdate($sourceObject, $propertiesToSkip);
	}
}
