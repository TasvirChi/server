<?php
/**
 * Short link service
 *
 * @service shortLink
 * @package plugins.shortLink
 * @subpackage api.services
 */
class ShortLinkService extends BorhanBaseService
{
	protected function partnerRequired($actionName)
	{
		if ($actionName === 'goto')
			return false;
			
		return parent::partnerRequired($actionName);
	}
	
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);

		if($actionName != 'goto')
		{
			$this->applyPartnerFilterForClass('ShortLink');
			$this->applyPartnerFilterForClass('kuser');
		}
	}
	
	/**
	 * List short link objects by filter and pager
	 * 
	 * @action list
	 * @param BorhanShortLinkFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanShortLinkListResponse
	 */
	function listAction(BorhanShortLinkFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanShortLinkFilter;
			
		$shortLinkFilter = $filter->toFilter($this->getPartnerId());
		
		$c = new Criteria();
		$shortLinkFilter->attachToCriteria($c);
		$count = ShortLinkPeer::doCount($c);
		
		if (! $pager)
			$pager = new BorhanFilterPager ();
		$pager->attachToCriteria ( $c );
		$list = ShortLinkPeer::doSelect($c);
		
		$response = new BorhanShortLinkListResponse();
		$response->objects = BorhanShortLinkArray::fromDbArray($list, $this->getResponseProfile());
		$response->totalCount = $count;
		
		return $response;
	}
	
	/**
	 * Allows you to add a short link object
	 * 
	 * @action add
	 * @param BorhanShortLink $shortLink
	 * @return BorhanShortLink
	 */
	function addAction(BorhanShortLink $shortLink)
	{
		$shortLink->validatePropertyNotNull('systemName');
		$shortLink->validatePropertyMinLength('systemName', 3);
		$shortLink->validatePropertyNotNull('fullUrl');
		$shortLink->validatePropertyMinLength('fullUrl', 10);
		
		if(!$shortLink->status)
			$shortLink->status = BorhanShortLinkStatus::ENABLED;
			
		if(!$shortLink->userId)
			$shortLink->userId = $this->getKuser()->getPuserId();
			
		$dbShortLink = new ShortLink();
		$dbShortLink = $shortLink->toInsertableObject($dbShortLink, array('userId'));
		$dbShortLink->setPartnerId($this->getPartnerId());
		$dbShortLink->setPuserId(is_null($shortLink->userId) ? $this->getKuser()->getPuserId() : $shortLink->userId);
		$dbShortLink->save();
		
		$shortLink = new BorhanShortLink();
		$shortLink->fromObject($dbShortLink, $this->getResponseProfile());
		
		return $shortLink;
	}
	
	/**
	 * Retrieve an short link object by id
	 * 
	 * @action get
	 * @param string $id 
	 * @return BorhanShortLink
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */		
	function getAction($id)
	{
		$dbShortLink = ShortLinkPeer::retrieveByPK($id);
		
		if(!$dbShortLink)
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $id);
			
		$shortLink = new BorhanShortLink();
		$shortLink->fromObject($dbShortLink, $this->getResponseProfile());
		
		return $shortLink;
	}


	/**
	 * Update exisitng short link
	 * 
	 * @action update
	 * @param string $id
	 * @param BorhanShortLink $shortLink
	 * @return BorhanShortLink
	 *
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */	
	function updateAction($id, BorhanShortLink $shortLink)
	{
		$dbShortLink = ShortLinkPeer::retrieveByPK($id);
	
		if (!$dbShortLink)
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $id);
		
		$dbShortLink = $shortLink->toUpdatableObject($dbShortLink);
		$dbShortLink->save();
	
		$shortLink->fromObject($dbShortLink, $this->getResponseProfile());
		
		return $shortLink;
	}

	/**
	 * Mark the short link as deleted
	 * 
	 * @action delete
	 * @param string $id 
	 * @return BorhanShortLink
	 *
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */		
	function deleteAction($id)
	{
		$dbShortLink = ShortLinkPeer::retrieveByPK($id);
	
		if (!$dbShortLink)
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $id);
		
		$dbShortLink->setStatus(BorhanShortLinkStatus::DELETED);
		$dbShortLink->save();
			
		$shortLink = new BorhanShortLink();
		$shortLink->fromObject($dbShortLink, $this->getResponseProfile());
		
		return $shortLink;
	}

	/**
	 * Serves short link
	 * 
	 * @action goto
	 * @param string $id
	 * @param bool $proxy proxy the response instead of redirect
	 * @return file
	 * 
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */		
	function gotoAction($id, $proxy = false)
	{
		BorhanResponseCacher::disableCache();
		
		$dbShortLink = ShortLinkPeer::retrieveByPK($id);
	
		if (!$dbShortLink)
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $id);

		if($proxy)
			kFileUtils::dumpUrl($dbShortLink->getFullUrl(), true, true);
		
		header('Location: ' . $dbShortLink->getFullUrl());
		die;
	}
}
