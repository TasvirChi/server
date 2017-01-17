<?php

/**
 * Manage response profiles
 *
 * @service responseProfile
 */
class ResponseProfileService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		
		//Don;t apply partner filter if action is list to avoid returning default partner 0 response profiles on every call
		if($actionName !== "list")
			$this->applyPartnerFilterForClass('ResponseProfile'); 	
	}
	
	/* (non-PHPdoc)
	 * @see BorhanBaseService::partnerGroup()
	 */
	protected function partnerGroup($peer = null)
	{
		
		switch ($this->actionName)
		{
			case 'get':
				return $this->partnerGroup . ',0';
		}
			
		return $this->partnerGroup;
	}
	
	/**
	 * Add new response profile
	 * 
	 * @action add
	 * @param BorhanResponseProfile $addResponseProfile
	 * @return BorhanResponseProfile
	 */
	function addAction(BorhanResponseProfile $addResponseProfile)
	{
		$dbResponseProfile = $addResponseProfile->toInsertableObject();
		/* @var $dbResponseProfile ResponseProfile */
		$dbResponseProfile->setPartnerId($this->getPartnerId());
		$dbResponseProfile->setStatus(ResponseProfileStatus::ENABLED);
		$dbResponseProfile->save();
		
		$addResponseProfile = new BorhanResponseProfile();
		$addResponseProfile->fromObject($dbResponseProfile, $this->getResponseProfile());
		return $addResponseProfile;
	}
	
	/**
	 * Get response profile by id
	 * 
	 * @action get
	 * @param int $id
	 * @return BorhanResponseProfile
	 * 
	 * @throws BorhanErrors::RESPONSE_PROFILE_ID_NOT_FOUND
	 */
	function getAction($id)
	{
		$dbResponseProfile = ResponseProfilePeer::retrieveByPK($id);
		if (!$dbResponseProfile)
			throw new BorhanAPIException(BorhanErrors::RESPONSE_PROFILE_ID_NOT_FOUND, $id);
			
		$responseProfile = new BorhanResponseProfile();
		$responseProfile->fromObject($dbResponseProfile, $this->getResponseProfile());
		return $responseProfile;
	}
	
	/**
	 * Update response profile by id
	 * 
	 * @action update
	 * @param int $id
	 * @param BorhanResponseProfile $updateResponseProfile
	 * @return BorhanResponseProfile
	 * 
	 * @throws BorhanErrors::RESPONSE_PROFILE_ID_NOT_FOUND
	 */
	function updateAction($id, BorhanResponseProfile $updateResponseProfile)
	{
		$dbResponseProfile = ResponseProfilePeer::retrieveByPK($id);
		if (!$dbResponseProfile)
			throw new BorhanAPIException(BorhanErrors::RESPONSE_PROFILE_ID_NOT_FOUND, $id);
		
		$updateResponseProfile->toUpdatableObject($dbResponseProfile);
		$dbResponseProfile->save();
		
		$updateResponseProfile = new BorhanResponseProfile();
		$updateResponseProfile->fromObject($dbResponseProfile, $this->getResponseProfile());
		return $updateResponseProfile;
	}

	/**
	 * Update response profile status by id
	 * 
	 * @action updateStatus
	 * @param int $id
	 * @param BorhanResponseProfileStatus $status
	 * @return BorhanResponseProfile
	 * 
	 * @throws BorhanErrors::RESPONSE_PROFILE_ID_NOT_FOUND
	 */
	function updateStatusAction($id, $status)
	{
		$dbResponseProfile = ResponseProfilePeer::retrieveByPK($id);
		if (!$dbResponseProfile)
			throw new BorhanAPIException(BorhanErrors::RESPONSE_PROFILE_ID_NOT_FOUND, $id);

		if($status == BorhanResponseProfileStatus::ENABLED)
		{
			//Check uniqueness of new object's system name
			$systemNameProfile = ResponseProfilePeer::retrieveBySystemName($dbResponseProfile->getSystemName(), $id);
			if ($systemNameProfile)
				throw new BorhanAPIException(BorhanErrors::RESPONSE_PROFILE_DUPLICATE_SYSTEM_NAME, $dbResponseProfile->getSystemName());
		}	
		
		$dbResponseProfile->setStatus($status);
		$dbResponseProfile->save();
	
		$responseProfile = new BorhanResponseProfile();
		$responseProfile->fromObject($dbResponseProfile, $this->getResponseProfile());
		return $responseProfile;
	}
	
	/**
	 * Delete response profile by id
	 * 
	 * @action delete
	 * @param int $id
	 * 
	 * @throws BorhanErrors::RESPONSE_PROFILE_ID_NOT_FOUND
	 */
	function deleteAction($id)
	{
		$dbResponseProfile = ResponseProfilePeer::retrieveByPK($id);
		if (!$dbResponseProfile)
			throw new BorhanAPIException(BorhanErrors::RESPONSE_PROFILE_ID_NOT_FOUND, $id);

		$dbResponseProfile->setStatus(ResponseProfileStatus::DELETED);
		$dbResponseProfile->save();
	}
	
	/**
	 * List response profiles by filter and pager
	 * 
	 * @action list
	 * @param BorhanFilterPager $filter
	 * @param BorhanResponseProfileFilter $pager
	 * @return BorhanResponseProfileListResponse
	 */
	function listAction(BorhanResponseProfileFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanResponseProfileFilter();
		
		//Add partner 0 to filter only in case systemNmae or Id are provided in the filter to avoid returning it by default
		if(isset($filter->systemNameEqual) || isset($filter->idEqual)) {
			$this->partnerGroup .= ",0";
		}
		$this->applyPartnerFilterForClass('ResponseProfile');

		if (!$pager)
			$pager = new BorhanFilterPager();
			
		$responseProfileFilter = new ResponseProfileFilter();
		$filter->toObject($responseProfileFilter);

		$c = new Criteria();
		$responseProfileFilter->attachToCriteria($c);
		
		$totalCount = ResponseProfilePeer::doCount($c);
		
		$pager->attachToCriteria($c);
		$dbList = ResponseProfilePeer::doSelect($c);
		
		$list = BorhanResponseProfileArray::fromDbArray($dbList, $this->getResponseProfile());
		$response = new BorhanResponseProfileListResponse();
		$response->objects = $list;
		$response->totalCount = $totalCount;
		return $response;
	}
	
	/**
	 * Recalculate response profile cached objects
	 * 
	 * @action recalculate
	 * @param BorhanResponseProfileCacheRecalculateOptions $options
	 * @return BorhanResponseProfileCacheRecalculateResults
	 */
	function recalculateAction(BorhanResponseProfileCacheRecalculateOptions $options)
	{
		return BorhanResponseProfileCacher::recalculateCacheBySessionType($options);
	}
	
	/**
	 * Clone an existing response profile
	 * 
	 * @action clone
	 * @param int $id
	 * @param BorhanResponseProfile $profile
	 * @throws BorhanErrors::RESPONSE_PROFILE_ID_NOT_FOUND
	 * @throws BorhanErrors::RESPONSE_PROFILE_DUPLICATE_SYSTEM_NAME
	 * @return BorhanResponseProfile
	 */
	function cloneAction ($id, BorhanResponseProfile $profile)
	{
		$origResponseProfileDbObject = ResponseProfilePeer::retrieveByPK($id);
		if (!$origResponseProfileDbObject)
			throw new BorhanAPIException(BorhanErrors::RESPONSE_PROFILE_ID_NOT_FOUND, $id);
			
		$newResponseProfileDbObject = $origResponseProfileDbObject->copy();
		
		if ($profile)
			$newResponseProfileDbObject = $profile->toInsertableObject($newResponseProfileDbObject);
				
		$newResponseProfileDbObject->save();
		
		$newResponseProfile = new BorhanResponseProfile();
		$newResponseProfile->fromObject($newResponseProfileDbObject, $this->getResponseProfile());
		return $newResponseProfile;
	}
}