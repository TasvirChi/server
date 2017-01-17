<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanUserEntryFilter extends BorhanUserEntryBaseFilter
{

	/**
	 * @var BorhanNullableBoolean
	 */
	public $userIdEqualCurrent;

	/**
	 * @var BorhanNullableBoolean
	 */
	public $isAnonymous;
	
	/**
	 * @return baseObjectFilter
	 */
	protected function getCoreFilter()
	{
		return new UserEntryFilter();
	}

	/**
	 * @param BorhanFilterPager $pager
	 * @param BorhanDetachedResponseProfile $responseProfile
	 * @return BorhanListResponse
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{

		$response = new BorhanUserEntryListResponse();
		if ( in_array(kCurrentContext::getCurrentSessionType(), array(kSessionBase::SESSION_TYPE_NONE,kSessionBase::SESSION_TYPE_WIDGET)) )
		{
			$response->totalCount = 0;
			return $response;
		}


		$c = new Criteria();
		if (!is_null($this->userIdEqualCurrent) && $this->userIdEqualCurrent)
		{
			$this->userIdEqual = kCurrentContext::getCurrentKsKuserId();
		}
		else
		{
			$this->fixFilterUserId();
		}
		$userEntryFilter = $this->toObject();
		$userEntryFilter->attachToCriteria($c);

		$pager->attachToCriteria($c);
		$list = UserEntryPeer::doSelect($c);

		$resultCount = count($list);
		if ($resultCount && ($resultCount < $pager->pageSize))
		{
			$totalCount = ($pager->pageIndex - 1) * $pager->pageSize + $resultCount;
		}
		else
		{
			BorhanFilterPager::detachFromCriteria($c);
			$totalCount = UserEntryPeer::doCount($c);
		}

		$response->totalCount = $totalCount;
		$response->objects = BorhanUserEntryArray::fromDbArray($list, $responseProfile);
		return $response;
	}


	/**
	 * The user_id is infact a puser_id and the kuser_id should be retrieved
	 */
	protected function fixFilterUserId()
	{
		if ($this->userIdEqual !== null)
		{
			$kuser = kuserPeer::getKuserByPartnerAndUid(kCurrentContext::getCurrentPartnerId(), $this->userIdEqual);
			if ($kuser)
				$this->userIdEqual = $kuser->getId();
			else
				$this->userIdEqual = -1; // no result will be returned when the user is missing
		}

		if(!empty($this->userIdIn))
		{
			$this->userIdIn = $this->preparePusersToKusersFilter( $this->userIdIn );
		}
		if(!empty($this->userIdNotIn))
		{
			$this->userIdNotIn = $this->preparePusersToKusersFilter( $this->userIdNotIn );
		}

		if(!is_null($this->isAnonymous))
		{
			if(BorhanNullableBoolean::toBoolean($this->isAnonymous)===false)
				$this->userIdNotIn .= self::getListOfAnonymousUsers();

			elseif(BorhanNullableBoolean::toBoolean($this->isAnonymous)===true)
				$this->userIdIn .= self::getListOfAnonymousUsers();
		}
	}

	public static function getListOfAnonymousUsers()
	{
		$anonKuserIds = "";
		$anonKusers = kuserPeer::getKuserByPartnerAndUids(kCurrentContext::getCurrentPartnerId(), array(0,''));
		foreach ($anonKusers as $anonKuser) {
			$anonKuserIds .= ",".$anonKuser->getKuserId();
		}
		return $anonKuserIds;
	}
}
