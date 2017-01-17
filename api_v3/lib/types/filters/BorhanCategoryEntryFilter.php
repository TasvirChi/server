<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanCategoryEntryFilter extends BorhanCategoryEntryBaseFilter
{
	
	
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new categoryEntryFilter();
	}

	/* (non-PHPdoc)
	 * @see BorhanRelatedFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		if ($this->entryIdEqual == null &&
			$this->categoryIdIn == null &&
			$this->categoryIdEqual == null && 
			(kEntitlementUtils::getEntitlementEnforcement() || !kCurrentContext::$is_admin_session))
			throw new BorhanAPIException(BorhanErrors::MUST_FILTER_ON_ENTRY_OR_CATEGORY);		
			
		if(kEntitlementUtils::getEntitlementEnforcement())
		{
			//validate entitl for entry
			if($this->entryIdEqual != null)
			{
				$entry = entryPeer::retrieveByPK($this->entryIdEqual);
				if(!$entry)
					throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $this->entryIdEqual);
			}
			
			//validate entitl for entryIn
			if($this->entryIdIn != null)
			{
				$entry = entryPeer::retrieveByPKs($this->entryIdIn);
				if(!$entry)
					throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $this->entryIdIn);
			}
			
			//validate entitl categories
			if($this->categoryIdIn != null)
			{
				$categoryIdInArr = explode(',', $this->categoryIdIn);
				if(!categoryKuserPeer::areCategoriesAllowed($categoryIdInArr))
				$categoryIdInArr = array_unique($categoryIdInArr);
				
				$entitledCategories = categoryPeer::retrieveByPKs($categoryIdInArr);
				
				if(!count($entitledCategories) || count($entitledCategories) != count($categoryIdInArr))
					throw new BorhanAPIException(BorhanErrors::CATEGORY_NOT_FOUND, $this->categoryIdIn);
					
				$categoriesIdsUnlisted = array();
				foreach($entitledCategories as $category)
				{
					if($category->getDisplayInSearch() == DisplayInSearchType::CATEGORY_MEMBERS_ONLY)
						$categoriesIdsUnlisted[] = $category->getId();
				}

				if(count($categoriesIdsUnlisted))
				{
					if(!categoryKuserPeer::areCategoriesAllowed($categoriesIdsUnlisted))
						throw new BorhanAPIException(BorhanErrors::CATEGORY_NOT_FOUND, $this->categoryIdIn);
				}
			}
			
			//validate entitl category
			if($this->categoryIdEqual != null)
			{
				$category = categoryPeer::retrieveByPK($this->categoryIdEqual);
				if(!$category && kCurrentContext::$master_partner_id != Partner::BATCH_PARTNER_ID)
					throw new BorhanAPIException(BorhanErrors::CATEGORY_NOT_FOUND, $this->categoryIdEqual);

				if(($category->getDisplayInSearch() == DisplayInSearchType::CATEGORY_MEMBERS_ONLY) && 
					!categoryKuserPeer::retrievePermittedKuserInCategory($category->getId(), kCurrentContext::getCurrentKsKuserId()))
				{
					throw new BorhanAPIException(BorhanErrors::CATEGORY_NOT_FOUND, $this->categoryIdEqual);
				}
			}
		}
			
		$this->fixUserIds();
		$categoryEntryFilter = $this->toObject();
		 
		$c = BorhanCriteria::create(categoryEntryPeer::OM_CLASS);
		$categoryEntryFilter->attachToCriteria($c);
		
		if(!kEntitlementUtils::getEntitlementEnforcement() || $this->entryIdEqual == null)
			$pager->attachToCriteria($c);
			
		$dbCategoriesEntry = categoryEntryPeer::doSelect($c);
		
		if(kEntitlementUtils::getEntitlementEnforcement() && count($dbCategoriesEntry) && $this->entryIdEqual != null)
		{
			//remove unlisted categories: display in search is set to members only
			$categoriesIds = array();
			foreach ($dbCategoriesEntry as $dbCategoryEntry)
				$categoriesIds[] = $dbCategoryEntry->getCategoryId();
				
			$c = BorhanCriteria::create(categoryPeer::OM_CLASS);
			$c->add(categoryPeer::ID, $categoriesIds, Criteria::IN);
			$pager->attachToCriteria($c);
			$c->applyFilters();
			
			$categoryIds = $c->getFetchedIds();
			
			foreach ($dbCategoriesEntry as $key => $dbCategoryEntry)
			{
				if(!in_array($dbCategoryEntry->getCategoryId(), $categoryIds))
				{
					BorhanLog::info('Category [' . print_r($dbCategoryEntry->getCategoryId(),true) . '] is not listed to user');
					unset($dbCategoriesEntry[$key]);
				}
			}
			
			$totalCount = $c->getRecordsCount();
		}
		else
		{
			$resultCount = count($dbCategoriesEntry);
			if ($resultCount && $resultCount < $pager->pageSize)
				$totalCount = ($pager->pageIndex - 1) * $pager->pageSize + $resultCount;
			else
			{
				BorhanFilterPager::detachFromCriteria($c);
				$totalCount = categoryEntryPeer::doCount($c);
			}
		}
			
		$categoryEntrylist = BorhanCategoryEntryArray::fromDbArray($dbCategoriesEntry, $responseProfile);
		$response = new BorhanCategoryEntryListResponse();
		$response->objects = $categoryEntrylist;
		$response->totalCount = $totalCount; // no pager since category entry is limited to ENTRY::MAX_CATEGORIES_PER_ENTRY
		return $response;
	}

	/* (non-PHPdoc)
	 * @see BorhanRelatedFilter::validateForResponseProfile()
	 */
	public function validateForResponseProfile()
	{
		if(kEntitlementUtils::getEntitlementEnforcement())
		{
			if(PermissionPeer::isValidForPartner(PermissionName::FEATURE_ENABLE_RESPONSE_PROFILE_USER_CACHE, kCurrentContext::getCurrentPartnerId()))
			{
				BorhanResponseProfileCacher::useUserCache();
				return;
			}
			
			throw new BorhanAPIException(BorhanErrors::CANNOT_LIST_RELATED_ENTITLED_WHEN_ENTITLEMENT_IS_ENABLE, get_class($this));
		}
	}
	
	private function fixUserIds ()
	{
		if ($this->creatorUserIdEqual !== null)
		{
			$kuser = kuserPeer::getKuserByPartnerAndUid(kCurrentContext::getCurrentPartnerId(), $this->creatorUserIdEqual);
			if ($kuser)
				$this->creatorUserIdEqual = $kuser->getId();
			else 
				$this->creatorUserIdEqual = -1; // no result will be returned when the user is missing
		}

		if(!empty($this->creatorUserIdIn))
		{
			$this->creatorUserIdIn = $this->preparePusersToKusersFilter( $this->creatorUserIdIn );
		}
	}
}
