<?php

/**
 * Add & Manage CategoryUser - membership of a user in a category
 *
 * @service categoryUser
 */
class CategoryUserService extends BorhanBaseService
{
	/**
	 * Add new CategoryUser
	 * 
	 * @action add
	 * @param BorhanCategoryUser $categoryUser
	 * @return BorhanCategoryUser
	 */
	function addAction(BorhanCategoryUser $categoryUser)
	{
		$dbCategoryKuser = $categoryUser->toInsertableObject();
		/* @var $dbCategoryKuser categoryKuser */
		$category = categoryPeer::retrieveByPK($categoryUser->categoryId);
		if (!$category)
			throw new BorhanAPIException(BorhanErrors::CATEGORY_NOT_FOUND, $categoryUser->categoryId);

		$maxUserPerCategory=kConf::get('max_users_per_category');
		if($category->getMembersCount() >= $maxUserPerCategory)
			throw new BorhanAPIException(BorhanErrors::CATEGORY_MAX_USER_REACHED,$maxUserPerCategory);

		$currentKuserCategoryKuser = categoryKuserPeer::retrievePermittedKuserInCategory($categoryUser->categoryId, kCurrentContext::getCurrentKsKuserId());
		if (!kEntitlementUtils::getEntitlementEnforcement())
		{
			$dbCategoryKuser->setStatus(CategoryKuserStatus::ACTIVE);	
			$dbCategoryKuser->setPermissionLevel($categoryUser->permissionLevel);
		}
		elseif ($currentKuserCategoryKuser && $currentKuserCategoryKuser->getPermissionLevel() == CategoryKuserPermissionLevel::MANAGER)
		{
			//Current Kuser is manager
			$dbCategoryKuser->setStatus(CategoryKuserStatus::ACTIVE);
		}
		elseif ($category->getUserJoinPolicy() == UserJoinPolicyType::AUTO_JOIN)
		{
			$dbCategoryKuser->setPermissionLevel($category->getDefaultPermissionLevel());
			$dbCategoryKuser->setStatus(CategoryKuserStatus::ACTIVE);
		}
		elseif ($category->getUserJoinPolicy() == UserJoinPolicyType::REQUEST_TO_JOIN)
		{
			$dbCategoryKuser->setPermissionLevel($category->getDefaultPermissionLevel());
			$dbCategoryKuser->setStatus(CategoryKuserStatus::PENDING);
		}
		else
		{
			throw new BorhanAPIException(BorhanErrors::CATEGORY_USER_JOIN_NOT_ALLOWED, $categoryUser->categoryId);	
		}
				
		$dbCategoryKuser->setCategoryFullIds($category->getFullIds());
		$dbCategoryKuser->setPartnerId($this->getPartnerId());
		$dbCategoryKuser->save();
		
		$categoryUser->fromObject($dbCategoryKuser, $this->getResponseProfile());
		return $categoryUser;
	}
	
	/**
	 * Get CategoryUser by id
	 * 
	 * @action get
	 * @param int $categoryId
	 * @param string $userId
	 * @return BorhanCategoryUser
	 */
	function getAction($categoryId, $userId)
	{
		$partnerId = kCurrentContext::$partner_id ? kCurrentContext::$partner_id : kCurrentContext::$ks_partner_id;
		$kuser = kuserPeer::getKuserByPartnerAndUid($partnerId, $userId);
		if (!$kuser)
			throw new BorhanAPIException(BorhanErrors::INVALID_USER_ID, $userId);
			
		$category = categoryPeer::retrieveByPK($categoryId);
		if (!$category)
			throw new BorhanAPIException(BorhanErrors::CATEGORY_NOT_FOUND, $categoryId);						

		if($category->getInheritanceType() == InheritanceType::INHERIT)
			$categoryId = $category->getInheritedParentId();
					
		$dbCategoryKuser = categoryKuserPeer::retrieveByCategoryIdAndKuserId($categoryId, $kuser->getId());
		if (!$dbCategoryKuser)
			throw new BorhanAPIException(BorhanErrors::INVALID_CATEGORY_USER_ID, $categoryId, $userId);
			
		$categoryUser = new BorhanCategoryUser();
		$categoryUser->fromObject($dbCategoryKuser, $this->getResponseProfile());
		
		return $categoryUser;
	}
	
	/**
	 * Update CategoryUser by id
	 * 
	 * @action update
	 * @param int $categoryId
	 * @param string $userId
	 * @param BorhanCategoryUser $categoryUser
	 * @param bool $override - to override manual changes
	 * @return BorhanCategoryUser
	 */
	function updateAction($categoryId, $userId, BorhanCategoryUser $categoryUser, $override = false)
	{
		$partnerId = kCurrentContext::$partner_id ? kCurrentContext::$partner_id : kCurrentContext::$ks_partner_id;
		$kuser = kuserPeer::getKuserByPartnerAndUid($partnerId, $userId);
		if (!$kuser)
			throw new BorhanAPIException(BorhanErrors::INVALID_USER_ID, $userId);
		
		$dbCategoryKuser = categoryKuserPeer::retrieveByCategoryIdAndKuserId($categoryId, $kuser->getId());
		if (!$dbCategoryKuser)
			throw new BorhanAPIException(BorhanErrors::INVALID_CATEGORY_USER_ID, $categoryId, $userId);
			
		if(!$override && 
			($categoryUser->updateMethod == null || $categoryUser->updateMethod == BorhanUpdateMethodType::AUTOMATIC) && 
			$dbCategoryKuser->getUpdateMethod() == BorhanUpdateMethodType::MANUAL)
			throw new BorhanAPIException(BorhanErrors::CANNOT_OVERRIDE_MANUAL_CHANGES);
		
		$dbCategoryKuser = $categoryUser->toUpdatableObject($dbCategoryKuser);
				
		$dbCategoryKuser->save();
		
		$categoryUser->fromObject($dbCategoryKuser, $this->getResponseProfile());
		return $categoryUser;
		
	}
	
	/**
	 * Delete a CategoryUser
	 * 
	 * @action delete
	 * @param int $categoryId
	 * @param string $userId
	 */
	function deleteAction($categoryId, $userId)
	{
		$partnerId = kCurrentContext::$partner_id ? kCurrentContext::$partner_id : kCurrentContext::$ks_partner_id;
		$kuser = kuserPeer::getKuserByPartnerAndUid($partnerId, $userId);
		
		if (!$kuser)
		{	
			if (kCurrentContext::$master_partner_id != Partner::BATCH_PARTNER_ID)
				throw new BorhanAPIException(BorhanErrors::INVALID_USER_ID, $userId);
			
			kuserPeer::setUseCriteriaFilter(false);
			$kuser = kuserPeer::getKuserByPartnerAndUid($partnerId, $userId);
			kuserPeer::setUseCriteriaFilter(true);
			
			if (!$kuser)
				throw new BorhanAPIException(BorhanErrors::INVALID_USER_ID, $userId);
		}
			
		$dbCategoryKuser = categoryKuserPeer::retrieveByCategoryIdAndKuserId($categoryId, $kuser->getId());
		/* @var $dbCategoryKuser categoryKuser */
		if (!$dbCategoryKuser)
			throw new BorhanAPIException(BorhanErrors::INVALID_CATEGORY_USER_ID, $categoryId, $userId);
			
		$category = categoryPeer::retrieveByPK($categoryId);
		if (!$category)
			throw new BorhanAPIException(BorhanErrors::CATEGORY_NOT_FOUND, $categoryId);						

		if ($category->getInheritanceType() == InheritanceType::INHERIT && kCurrentContext::$master_partner_id != Partner::BATCH_PARTNER_ID)
			throw new BorhanAPIException(BorhanErrors::CATEGORY_INHERIT_MEMBERS, $categoryId);		
		
		// only manager can remove memnger or users remove himself
		$currentKuserCategoryKuser = categoryKuserPeer::retrievePermittedKuserInCategory($dbCategoryKuser->getCategoryId());
		if((!$currentKuserCategoryKuser || 
			($currentKuserCategoryKuser->getPermissionLevel() != CategoryKuserPermissionLevel::MANAGER &&
			 kCurrentContext::$ks_uid != $userId)) &&
			 kCurrentContext::$ks_partner_id != Partner::BATCH_PARTNER_ID &&
			 kEntitlementUtils::getEntitlementEnforcement())
			throw new BorhanAPIException(BorhanErrors::CANNOT_UPDATE_CATEGORY_USER);
		
		if($dbCategoryKuser->getKuserId() == $category->getKuserId() &&
			kCurrentContext::$ks_partner_id != Partner::BATCH_PARTNER_ID)
			throw new BorhanAPIException(BorhanErrors::CANNOT_UPDATE_CATEGORY_USER_OWNER);
			
		$dbCategoryKuser->setStatus(CategoryKuserStatus::DELETED);
		$dbCategoryKuser->save();
	} 
	
	/**
	 * activate CategoryUser
	 * 
	 * @action activate
	 * @param int $categoryId
	 * @param string $userId
	 * @return BorhanCategoryUser
	 */
	function activateAction($categoryId, $userId)
	{
		$partnerId = kCurrentContext::$partner_id ? kCurrentContext::$partner_id : kCurrentContext::$ks_partner_id;
		$kuser = kuserPeer::getKuserByPartnerAndUid($partnerId, $userId);
		if (!$kuser)
			throw new BorhanAPIException(BorhanErrors::INVALID_USER_ID, $userId);
			
		$dbCategoryKuser = categoryKuserPeer::retrieveByCategoryIdAndKuserId($categoryId, $kuser->getId());
		if (!$dbCategoryKuser)
			throw new BorhanAPIException(BorhanErrors::INVALID_CATEGORY_USER_ID, $categoryId, $userId);
		
		$currentKuserCategoryKuser = categoryKuserPeer::retrievePermittedKuserInCategory($dbCategoryKuser->getCategoryId(), kCurrentContext::getCurrentKsKuserId());
		if(kEntitlementUtils::getEntitlementEnforcement() &&
			(!$currentKuserCategoryKuser || $currentKuserCategoryKuser->getPermissionLevel() != CategoryKuserPermissionLevel::MANAGER))
			throw new BorhanAPIException(BorhanErrors::CANNOT_UPDATE_CATEGORY_USER);
		
		$dbCategoryKuser->setStatus(CategoryKuserStatus::ACTIVE);
		$dbCategoryKuser->save();
		
		$categoryUser = new BorhanCategoryUser();
		$categoryUser->fromObject($dbCategoryKuser, $this->getResponseProfile());
		return $categoryUser;
	} 
	
	/**
	 * reject CategoryUser
	 * 
	 * @action deactivate
	 * @param int $categoryId
	 * @param string $userId
	 * @return BorhanCategoryUser
	 */
	function deactivateAction($categoryId, $userId)
	{
		$partnerId = kCurrentContext::$partner_id ? kCurrentContext::$partner_id : kCurrentContext::$ks_partner_id;
		$kuser = kuserPeer::getKuserByPartnerAndUid($partnerId, $userId);
		if (!$kuser)
			throw new BorhanAPIException(BorhanErrors::INVALID_USER_ID, $userId);
			
		$dbCategoryKuser = categoryKuserPeer::retrieveByCategoryIdAndKuserId($categoryId, $kuser->getId());
		if (!$dbCategoryKuser)
			throw new BorhanAPIException(BorhanErrors::INVALID_CATEGORY_USER_ID, $categoryId, $userId);
		
		$currentKuserCategoryKuser = categoryKuserPeer::retrievePermittedKuserInCategory($dbCategoryKuser->getCategoryId(), kCurrentContext::getCurrentKsKuserId());
		if(kEntitlementUtils::getEntitlementEnforcement() &&
			(!$currentKuserCategoryKuser || 
			($currentKuserCategoryKuser->getPermissionLevel() != CategoryKuserPermissionLevel::MANAGER &&
			 kCurrentContext::$ks_uid != $userId)))
			throw new BorhanAPIException(BorhanErrors::CANNOT_UPDATE_CATEGORY_USER);
		
		$dbCategoryKuser->setStatus(CategoryKuserStatus::NOT_ACTIVE);
		$dbCategoryKuser->save();
		
		$categoryUser = new BorhanCategoryUser();
		$categoryUser->fromObject($dbCategoryKuser, $this->getResponseProfile());
		return $categoryUser;
	} 
	
	
	/**
	 * List all categories
	 * 
	 * @action list
	 * @param BorhanCategoryUserFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanCategoryUserListResponse
	 * @throws BorhanErrors::MUST_FILTER_USERS_OR_CATEGORY
	 */
	function listAction(BorhanCategoryUserFilter $filter = null, BorhanFilterPager $pager = null)
	{	
		if (!$filter || !($filter->categoryIdEqual || $filter->categoryIdIn || $filter->categoryFullIdsStartsWith || $filter->categoryFullIdsEqual || $filter->userIdIn || $filter->userIdEqual || $filter->relatedGroupsByUserId))
			throw new BorhanAPIException(BorhanErrors::MUST_FILTER_USERS_OR_CATEGORY);	
			
		if(!$pager)
			$pager = new BorhanFilterPager();		
			
		return $filter->getListResponse($pager, $this->getResponseProfile());
	}
	
	/**
	 * Copy all memeber from parent category
	 * 
	 * @action copyFromCategory
	 * @param int $categoryId
	 */
	public function copyFromCategoryAction($categoryId)
	{
		if (kEntitlementUtils::getEntitlementEnforcement())
			throw new BorhanAPIException(BorhanErrors::CANNOT_UPDATE_CATEGORY_USER);
		
		$categoryDb = categoryPeer::retrieveByPK($categoryId);
		if (!$categoryDb)
			throw new BorhanAPIException(BorhanErrors::CATEGORY_NOT_FOUND, $categoryId);

		if($categoryDb->getParentId() == null)
			throw new BorhanAPIException(BorhanErrors::CATEGORY_DOES_NOT_HAVE_PARENT_CATEGORY);
		
		$categoryDb->copyCategoryUsersFromParent($categoryDb->getParentId());
	}
	
	/**
	 * Index CategoryUser by userid and category id
	 * 
	 * @action index
	 * @param string $userId
	 * @param int $categoryId
	 * @param bool $shouldUpdate
	 * @throws BorhanErrors::INVALID_CATEGORY_USER_ID
	 * @return int
	 */
	public function indexAction($userId, $categoryId, $shouldUpdate = true)
	{
		if(kEntitlementUtils::getEntitlementEnforcement())
			throw new BorhanAPIException(BorhanErrors::CANNOT_INDEX_OBJECT_WHEN_ENTITLEMENT_IS_ENABLE);
		
		$partnerId = kCurrentContext::$partner_id ? kCurrentContext::$partner_id : kCurrentContext::$ks_partner_id;
		$kuser = kuserPeer::getActiveKuserByPartnerAndUid($partnerId, $userId);

		if(!$kuser)
			throw new BorhanAPIException(BorhanErrors::INVALID_USER_ID);
			
		$dbCategoryKuser = categoryKuserPeer::retrievePermittedKuserInCategory($categoryId, $kuser->getId(), null, false);
		if(!$dbCategoryKuser)
			throw new BorhanAPIException(BorhanErrors::INVALID_CATEGORY_USER_ID);
			
		if (!$shouldUpdate)
		{
			$dbCategoryKuser->setUpdatedAt(time());
			$dbCategoryKuser->save();
			
			return $dbCategoryKuser->getId();
		}
				
		$dbCategoryKuser->reSetCategoryFullIds();
		$dbCategoryKuser->reSetScreenName();
		$dbCategoryKuser->save();
		
		return $dbCategoryKuser->getId();
	}
}
