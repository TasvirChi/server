<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanCategory extends BorhanObject implements IRelatedFilterable 
{
	/**
	 * The id of the Category
	 * 
	 * @var int
	 * @readonly
	 * @filter eq,in,notin
	 */
	public $id;
	
	/**
	 * 
	 * @var int
	 * @filter eq,in
	 */
	public $parentId;
	
	/**
	 * 
	 * @var int
	 * @readonly
	 * @filter order,eq
	 */
	public $depth;
	
	/**
	 * @var int
	 * @readonly
	 */
	public $partnerId;
	
	/**
	 * The name of the Category. 
	 * The following characters are not allowed: '<', '>', ','
	 * 
	 * @var string
	 * @filter order
	 */
	public $name;
	
	/**
	 * The full name of the Category
	 * 
	 * @var string
	 * @readonly
	 * @filter eq,likex,in, order
	 */
	public $fullName;
	
	/**
	 * The full ids of the Category
	 * 
	 * @var string
	 * @readonly
	 * @filter eq,likex, matchor
	 */
	public $fullIds;
	
	/**
	 * Number of entries in this Category (including child categories)
	 * 
	 * @var int
	 * @filter order
	 * @readonly
	 */
	public $entriesCount;
	
	/**
	 * Creation date as Unix timestamp (In seconds)
	 *  
	 * @var time
	 * @readonly
	 * @filter gte,lte,order
	 */
	public $createdAt;
	
	/**
	 * Update date as Unix timestamp (In seconds)
	 *  
	 * @var time
	 * @readonly
	 * @filter gte,lte,order
	 */
	public $updatedAt;
	
	/**
	 * Category description
	 * 
	 * @var string
	 */
	public $description;
	
	/**
	 * Category tags
	 * 
	 * @var string
	 * @filter like,mlikeor,mlikeand
	 */
	public $tags;
	
	/**
	 * If category will be returned for list action.
	 * 
	 * @var BorhanAppearInListType
	 * @filter eq
	 */
	public $appearInList;
	
	/**
	 * defines the privacy of the entries that assigned to this category
	 * 
	 * @var BorhanPrivacyType
	 * @filter eq,in
	 * @requiresPermission insert,update
	 */
	public $privacy;
	
	/**
	 * If Category members are inherited from parent category or set manualy. 
	 * @var BorhanInheritanceType
	 * @filter eq,in
	 * @requiresPermission insert,update
	 */
	public $inheritanceType;
	
	//userJoinPolicy is readonly only since product asked - and not because anything else. server code is working, and readonly doccomment can be remove
	
	/**
	 * Who can ask to join this category
	 *  
	 * @var BorhanUserJoinPolicyType
	 * @requiresPermission insert,update
	 * @readonly
	 */
	public $userJoinPolicy;
	
	/**
	 * Default permissionLevel for new users
	 *  
	 * @var BorhanCategoryUserPermissionLevel
	 * @requiresPermission insert,update
	 */
	public $defaultPermissionLevel;
	
	/**
	 * Category Owner (User id)
	 *  
	 * @var string
	 * @requiresPermission insert,update
	 */
	public $owner;
	
	/**
	 * Number of entries that belong to this category directly
	 *  
	 * @var int
	 * @filter order
	 * @readonly
	 */
	public $directEntriesCount;
	
	
	/**
	 * Category external id, controlled and managed by the partner.
	 *  
	 * @var string
	 * @filter eq,empty
	 */
	public $referenceId;
	
	/**
	 * who can assign entries to this category
	 *  
	 * @var BorhanContributionPolicyType
	 * @filter eq
	 * @requiresPermission insert,update
	 */
	public $contributionPolicy;
	
	/**
	 * Number of active members for this category
	 *  
	 * @var int
	 * @filter gte,lte,order
	 * @readonly
	 * 
	 */
	public $membersCount;
	
	/**
	 * Number of pending members for this category
	 *
	 * @var int
	 * @filter gte,lte
	 * @readonly
	 */
	public $pendingMembersCount;
	
	/**
	 * Set privacy context for search entries that assiged to private and public categories. the entries will be private if the search context is set with those categories.
	 *  
	 * @var string
	 * @filter eq
	 * @requiresPermission insert,update
	 */
	public $privacyContext;
	
	/**
	 * comma separated parents that defines a privacyContext for search
	 *
	 * @var string
	 * @readonly
	 */
	public $privacyContexts;
	
	/**
	 * Status
	 * 
	 * @var BorhanCategoryStatus
	 * @readonly
	 * @filter eq,in
	 */
	public $status;
	
	/**
	 * The category id that this category inherit its members and members permission (for contribution and join)
	 * 
	 * @var int
	 * @readonly
	 * @filter eq,in
	 */
	public $inheritedParentId;
	
	/**
	 * Can be used to store various partner related data as a numeric value
	 * 
	 * @var int
	 * @filter gte,lte,order
	 */
	public $partnerSortValue;
	
	/**
	 * Can be used to store various partner related data as a string 
	 * 
	 * @var string
	 */
	public $partnerData;
	
	/**
	 * Enable client side applications to define how to sort the category child categories 
	 * 
	 * @var BorhanCategoryOrderBy
	 */
	public $defaultOrderBy;
	
	/**
	 * 
	 * Number of direct children categories
	 * @filter order
	 * @var int
	 * @readonly
	 */
	public $directSubCategoriesCount;
	
	/**
	 * Moderation to add entries to this category by users that are not of permission level Manager or Moderator.  
	 * @var BorhanNullableBoolean
	 */
	public $moderation;
	
	/**
	 * Nunber of pending moderation entries
	 * @var int
	 * @readonly
	 */
	public $pendingEntriesCount;
	
	/**
 	 * Flag indicating that the category is an aggregation category
 	 * @requiresPermission insert,update 
 	 *  @var BorhanNullableBoolean
 	 */
 	public $isAggregationCategory;
 	
 	/**
 	 * List of aggregation channels the category belongs to
 	 * @filter mlikeor,mlikeand
 	 * @var string
 	 */
 	public $aggregationCategories;
	
	private static $mapBetweenObjects = array
	(
		"id",
		"parentId",
		"depth",
		"name",
		"fullName",
		"fullIds",
		"partnerId",
		"entriesCount",
		"createdAt",
		"updatedAt",
		"description",
		"tags",
		"appearInList" => "displayInSearch",
		"privacy",
		"inheritanceType",
		"userJoinPolicy",
		"defaultPermissionLevel",
		"owner" => "puserId",
		"directEntriesCount",
		"referenceId",
		"contributionPolicy",
		"membersCount",
		"pendingMembersCount",
		"privacyContext",	
		"privacyContexts",
		"status",
		"inheritedParentId",
		"partnerSortValue",
		"partnerData",
		"defaultOrderBy",
		"directSubCategoriesCount",
		"moderation",
		"pendingEntriesCount",
		"isAggregationCategory",
 		"aggregationCategories",
	);
	
	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
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
	 * validate parent id exists and if not - cannot set category to inherit from parent.
	 */
	public function validateParentId()
	{
		if ($this->parentId === null)
			$this->parentId = 0;
			
		if ($this->parentId !== 0)
		{
			$parentCategoryDb = categoryPeer::retrieveByPK($this->parentId);
			if (!$parentCategoryDb)
				throw new BorhanAPIException(BorhanErrors::PARENT_CATEGORY_NOT_FOUND, $this->parentId);
		}
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::validateForInsert()
	 */
	public function validateForInsert($propertiesToSkip = array())
	{
		$this->validatePropertyMinLength("name", 1);
		$this->validatePropertyMaxLength("name", categoryPeer::MAX_CATEGORY_NAME);
		$this->validateCategory();
		
		if ($this->parentId !== null)
		{
			$this->validateParentId();
		}
		elseif ($this->inheritanceType == BorhanInheritanceType::INHERIT)
		{
			//cannot inherit member with no parant
			throw new BorhanAPIException(BorhanErrors::CANNOT_INHERIT_MEMBERS_WHEN_PARENT_CATEGORY_IS_NOT_SET);
		}
		
		return parent::validateForInsert($propertiesToSkip);
	}

	/* (non-PHPdoc)
	 * @see BorhanObject::validateForUpdate()
	 */
	public function validateForUpdate($sourceObject, $propertiesToSkip = array())
	{
		if ($this->name !== null)
		{
			$this->validatePropertyMinLength("name", 1);
			$this->validatePropertyMaxLength("name", categoryPeer::MAX_CATEGORY_NAME);
		}
		
		if ($this->parentId !== null)
		{
			$this->validateParentId();
		}
		elseif ($this->inheritanceType == BorhanInheritanceType::INHERIT && 
		($this->parentId instanceof BorhanNullField || $sourceObject->getParentId() == null))
		{
			//cannot inherit member with no parant
			throw new BorhanAPIException(BorhanErrors::CANNOT_INHERIT_MEMBERS_WHEN_PARENT_CATEGORY_IS_NOT_SET);
		}
			
		$this->validateCategory($sourceObject);
			
		return parent::validateForUpdate($sourceObject, $propertiesToSkip);
	}
	
	/**
	 * validate category fields
	 * 1. category that inherit memebers cannot set values to inherited fields.
	 * 2. validate the owner id exists as kuser
	 * 
	 * @param category $sourceObject
	 */
	private function validateCategory(category $sourceObject = null)
	{
		if($this->privacyContext != null && kEntitlementUtils::getEntitlementEnforcement())
			throw new BorhanAPIException(BorhanErrors::CANNOT_UPDATE_CATEGORY_PRIVACY_CONTEXT);
			
		if(!$this->privacyContext && (!$sourceObject || !$sourceObject->getPrivacyContexts()))
		{
			$isInheritedPrivacyContext = true;
			if ($this->parentId != null)
			{
				$parentCategory = categoryPeer::retrieveByPK($this->parentId);
				if(!$parentCategory)
					throw new BorhanAPIException(BorhanErrors::CATEGORY_NOT_FOUND, $this->parentId);
				
				if($parentCategory->getPrivacyContexts() == '')
					$isInheritedPrivacyContext = false;
			}
			else
			{
				$isInheritedPrivacyContext = false;
			}
			
			if(!$isInheritedPrivacyContext)
			{
				if($this->appearInList != BorhanAppearInListType::PARTNER_ONLY && !$this->isNull('appearInList'))
					throw new BorhanAPIException(BorhanErrors::CANNOT_SET_APPEAR_IN_LIST_FIELD_WITH_NO_PRIVACY_CONTEXT);
				
				if ($this->inheritanceType != BorhanInheritanceType::MANUAL && !$this->isNull('inheritanceType'))
					throw new BorhanAPIException(BorhanErrors::CANNOT_SET_INHERITANCE_TYPE_FIELD_WITH_NO_PRIVACY_CONTEXT);
					 
				if ($this->privacy != BorhanPrivacyType::ALL && !$this->isNull('privacy'))
					throw new BorhanAPIException(BorhanErrors::CANNOT_SET_PRIVACY_FIELD_WITH_NO_PRIVACY_CONTEXT);
					 
				if (!$this->isNull('owner'))
					throw new BorhanAPIException(BorhanErrors::CANNOT_SET_OWNER_FIELD_WITH_NO_PRIVACY_CONTEXT);
	
				if ($this->userJoinPolicy != BorhanUserJoinPolicyType::NOT_ALLOWED && !$this->isNull('userJoinPolicy'))
					throw new BorhanAPIException(BorhanErrors::CANNOT_SET_USER_JOIN_POLICY_FIELD_WITH_NO_PRIVACY_CONTEXT);
				
				if ($this->contributionPolicy != BorhanContributionPolicyType::ALL  && !$this->isNull('contributionPolicy'))
				   throw new BorhanAPIException(BorhanErrors::CANNOT_SET_CONTIRUBUTION_POLICY_FIELD_WITH_NO_PRIVACY_CONTEXT);
				   
				if ($this->defaultPermissionLevel != BorhanCategoryUserPermissionLevel::MEMBER && !$this->isNull('defaultPermissionLevel'))
				   throw new BorhanAPIException(BorhanErrors::CANNOT_SET_DEFAULT_PERMISSION_LEVEL_FIELD_WITH_NO_PRIVACY_CONTEXT);
			}
		}
		
		if(($this->inheritanceType != BorhanInheritanceType::MANUAL && $this->inheritanceType != null) || 
			($this->inheritanceType == null && $sourceObject && $sourceObject->getInheritanceType() != BorhanInheritanceType::MANUAL))
		{	
			if ($this->owner != null)
			{
				if (!$sourceObject)
					throw new BorhanAPIException(BorhanErrors::CANNOT_SET_OWNER_WHEN_CATEGORY_INHERIT_MEMBERS);
				elseif ($this->owner != $sourceObject->getKuserId())
					throw new BorhanAPIException(BorhanErrors::CANNOT_SET_OWNER_WHEN_CATEGORY_INHERIT_MEMBERS);
			}
				
			if ($this->userJoinPolicy != null)
			{
				if (!$sourceObject)
					throw new BorhanAPIException(BorhanErrors::CANNOT_SET_USER_JOIN_POLICY_WHEN_CATEGORY_INHERIT_MEMBERS);
				elseif ($this->userJoinPolicy != $sourceObject->getUserJoinPolicy())
					throw new BorhanAPIException(BorhanErrors::CANNOT_SET_USER_JOIN_POLICY_WHEN_CATEGORY_INHERIT_MEMBERS);
			}
				
			if ($this->defaultPermissionLevel != null)
			{
				if (!$sourceObject)
					throw new BorhanAPIException(BorhanErrors::CANNOT_SET_DEFAULT_PERMISSION_LEVEL_WHEN_CATEGORY_INHERIT_MEMBERS);
				elseif ($this->defaultPermissionLevel != $sourceObject->getDefaultPermissionLevel())
					throw new BorhanAPIException(BorhanErrors::CANNOT_SET_DEFAULT_PERMISSION_LEVEL_WHEN_CATEGORY_INHERIT_MEMBERS);
			}
		}
		
		if (!is_null($sourceObject))
		{
			$partnerId = kCurrentContext::$partner_id ? kCurrentContext::$partner_id : kCurrentContext::$ks_partner_id;
			$partner = PartnerPeer::retrieveByPK($partnerId);
			if (!$partner || $partner->getFeaturesStatusByType(IndexObjectType::LOCK_CATEGORY))
				throw new BorhanAPIException(BorhanErrors::CATEGORIES_LOCKED);		
		}

		if ($this->owner && $this->owner != '' && !($this->owner instanceof BorhanNullField) )
		{
			if(!preg_match(kuser::PUSER_ID_REGEXP, $this->owner))
				throw new BorhanAPIException(BorhanErrors::CANNOT_SET_OWNER_FIELD_WITH_USER_ID, $this->owner);
		
			$partnerId = kCurrentContext::$partner_id ? kCurrentContext::$partner_id : kCurrentContext::$ks_partner_id;
			kuserPeer::createKuserForPartner($partnerId, $this->owner);
		}
		
		if (($this->isAggregationCategory && $this->aggregationCategories) ||
			($this->isAggregationCategory && $sourceObject && $sourceObject->getAggregationCategories()) ||
			($this->aggregationCategories && $sourceObject && $sourceObject->getIsAggregationCategory()))
			{
 				throw new BorhanAPIException(BorhanErrors::AGGREGATION_CATEGORY_WRONG_ASSOCIATION);
 			}
 			
 		if ($this->aggregationCategories)
 		{
 			$aggrCatIdsToCheck = explode (',' , $this->aggregationCategories);
 			foreach ($aggrCatIdsToCheck as $aggrCatIdToCheck)
 			{
 				$agrrCat = categoryPeer::retrieveByPK($aggrCatIdToCheck);
 				if (!$agrrCat)
 				{
 					throw new BorhanAPIException(BorhanErrors::CATEGORY_NOT_FOUND, $aggrCatIdToCheck);
 				}
 				if (!$agrrCat->getIsAggregationCategory())
 				{
 					throw new BorhanAPIException(BorhanErrors::AGGREGATION_CATEGORY_WRONG_ASSOCIATION);
 				}
 			}
 		}
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toInsertableObject($object_to_fill, $props_to_skip)
	 */
	public function toInsertableObject($object_to_fill = null , $props_to_skip = array())
	{
		$hasPrivacyContext = false;
		if ($this->privacyContext)
		{
			$hasPrivacyContext = true;
		}
		elseif ($this->parentId != null)
		{
			$parentCategory = categoryPeer::retrieveByPK($this->parentId);
			if(!$parentCategory)
				throw new BorhanAPIException(BorhanErrors::CATEGORY_NOT_FOUND, $this->parentId);
			
			if($parentCategory->getPrivacyContexts())
				$hasPrivacyContext = true;
		}
		
		if ($hasPrivacyContext)
		{
			if (!$this->owner && $this->inheritanceType != BorhanInheritanceType::INHERIT)
			{
				if (kCurrentContext::getCurrentKsKuser())
					$this->owner = kCurrentContext::getCurrentKsKuser()->getPuserId();
			}
		}
		
		return parent::toInsertableObject($object_to_fill, $props_to_skip);
	}
	
 	/* (non-PHPdoc)
	 * @see BorhanObject::toObject($object_to_fill, $props_to_skip)
	 */
	public function toObject($object_to_fill = null, $props_to_skip = array())
	{
		$this->trimStringProperties(array("name"));
		
		return parent::toObject($object_to_fill, $props_to_skip);
	}	
}