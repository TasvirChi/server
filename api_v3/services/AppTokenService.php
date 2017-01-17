<?php

/**
 * Manage application authentication tokens
 *
 * @service appToken
 */
class AppTokenService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		$this->applyPartnerFilterForClass('AppToken');
	}
	
	/**
	 * Add new application authentication token
	 * 
	 * @action add
	 * @param BorhanAppToken $appToken
	 * @return BorhanAppToken
	 */
	function addAction(BorhanAppToken $appToken)
	{
		$dbAppToken = $appToken->toInsertableObject();
		$dbAppToken->save();
		
		$appToken = new BorhanAppToken();
		$appToken->fromObject($dbAppToken, $this->getResponseProfile());
		return $appToken;
	}
	
	/**
	 * Get application authentication token by id
	 * 
	 * @action get
	 * @param string $id
	 * @return BorhanAppToken
	 * 
	 * @throws BorhanErrors::APP_TOKEN_ID_NOT_FOUND
	 */
	function getAction($id)
	{
		$dbAppToken = AppTokenPeer::retrieveByPK($id);
		if(!$dbAppToken)
			throw new BorhanAPIException(BorhanErrors::APP_TOKEN_ID_NOT_FOUND, $id);
		
		$appToken = new BorhanAppToken();
		$appToken->fromObject($dbAppToken, $this->getResponseProfile());
		return $appToken;
	}
	
	/**
	 * Update application authentication token by id
	 * 
	 * @action update
	 * @param string $id
	 * @param BorhanAppToken $appToken
	 * @return BorhanAppToken
	 * 
	 * @throws BorhanErrors::APP_TOKEN_ID_NOT_FOUND
	 */
	function updateAction($id, BorhanAppToken $appToken)
	{
		$dbAppToken = AppTokenPeer::retrieveByPK($id);
		if(!$dbAppToken)
			throw new BorhanAPIException(BorhanErrors::APP_TOKEN_ID_NOT_FOUND, $id);
		
		$appToken->toUpdatableObject($dbAppToken);
		$dbAppToken->save();
		
		$appToken = new BorhanAppToken();
		$appToken->fromObject($dbAppToken, $this->getResponseProfile());
		return $appToken;
	}
	
	/**
	 * Delete application authentication token by id
	 * 
	 * @action delete
	 * @param string $id
	 * 
	 * @throws BorhanErrors::APP_TOKEN_ID_NOT_FOUND
	 */
	function deleteAction($id)
	{
		$dbAppToken = AppTokenPeer::retrieveByPK($id);
		if(!$dbAppToken)
			throw new BorhanAPIException(BorhanErrors::APP_TOKEN_ID_NOT_FOUND, $id);
		
		$invalidSessionKey = ks::buildSessionIdHash($this->getPartnerId(), $id); 
		invalidSessionPeer::invalidateByKey($invalidSessionKey, invalidSession::INVALID_SESSION_TYPE_SESSION_ID, $dbAppToken->getExpiry());
		$dbAppToken->setStatus(AppTokenStatus::DELETED);
		$dbAppToken->save();
	}
	
	/**
	 * List application authentication tokens by filter and pager
	 * 
	 * @action list
	 * @param BorhanFilterPager $filter
	 * @param BorhanAppTokenFilter $pager
	 * @return BorhanAppTokenListResponse
	 */
	function listAction(BorhanAppTokenFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if(!$filter)
			$filter = new BorhanAppTokenFilter();
		
		if(!$pager)
			$pager = new BorhanFilterPager();
		
		$c = new Criteria();
		$appTokenFilter = $filter->toObject();
		$appTokenFilter->attachToCriteria($c);
		$pager->attachToCriteria($c);
		
		$list = AppTokenPeer::doSelect($c);
		
		$totalCount = null;
		$resultCount = count($list);
		if($resultCount && ($resultCount < $pager->pageSize))
		{
			$totalCount = ($pager->pageIndex - 1) * $pager->pageSize + $resultCount;
		}
		else
		{
			BorhanFilterPager::detachFromCriteria($c);
			$totalCount = AppTokenPeer::doCount($c);
		}
		
		$response = new BorhanAppTokenListResponse();
		$response->totalCount = $totalCount;
		$response->objects = BorhanAppTokenArray::fromDbArray($list, $this->getResponseProfile());
		return $response;
	}
	
	/**
	 * Starts a new KS (borhan Session) based on application authentication token id
	 * 
	 * @action startSession
	 * @param string $id application token id
	 * @param string $tokenHash hashed token, built of sha1 on current KS concatenated with the application token
	 * @param string $userId session user id, will be ignored if a different user id already defined on the application token
	 * @param BorhanSessionType $type session type, will be ignored if a different session type already defined on the application token
	 * @param int $expiry session expiry (in seconds), could be overwritten by shorter expiry of the application token and the session-expiry that defined on the application token 
	 * @throws BorhanErrors::APP_TOKEN_ID_NOT_FOUND
	 * @return BorhanSessionInfo
	 */
	function startSessionAction($id, $tokenHash, $userId = null, $type = null, $expiry = null)
	{
		$dbAppToken = AppTokenPeer::retrieveByPK($id);
		if(!$dbAppToken)
			throw new BorhanAPIException(BorhanErrors::APP_TOKEN_ID_NOT_FOUND, $id);
		
		if($dbAppToken->getStatus() != AppTokenStatus::ACTIVE)
			throw new BorhanAPIException(BorhanErrors::APP_TOKEN_NOT_ACTIVE, $id);
		
		$appTokenHash = $dbAppToken->calcHash();
		if($appTokenHash !== $tokenHash)
			throw new BorhanAPIException(BorhanErrors::INVALID_APP_TOKEN_HASH);
		
		BorhanResponseCacher::disableCache();
		
		$tokenExpiry = $dbAppToken->getSessionDuration();
		if(!is_null($dbAppToken->getExpiry()))
		{
			$tokenExpiry = min($tokenExpiry, $dbAppToken->getExpiry() - time());
			if($tokenExpiry < 0)
				throw new BorhanAPIException(BorhanErrors::APP_TOKEN_EXPIRED, $id);
		}
		if(!$expiry)
		{
			$expiry = $tokenExpiry;
		}
		$expiry = min($expiry, $tokenExpiry);
		
		if(!is_null($dbAppToken->getSessionType()))
			$type = $dbAppToken->getSessionType();
		if(is_null($type))
			$type = SessionType::USER;
			
		if(!is_null($dbAppToken->getSessionUserId()))
			$userId = $dbAppToken->getSessionUserId();
			
		$partnerId = kCurrentContext::getCurrentPartnerId();
		$partner = PartnerPeer::retrieveByPK($partnerId);
		$secret = $type == SessionType::ADMIN ? $partner->getAdminSecret() : $partner->getSecret();
		
		$privilegesArray = array(
			ks::PRIVILEGE_SESSION_ID => array($id),
			ks::PRIVILEGE_APP_TOKEN => array($id)
		);
		if($dbAppToken->getSessionPrivileges())
		{
			$privilegesArray = array_merge_recursive($privilegesArray, ks::parsePrivileges($dbAppToken->getSessionPrivileges()));
		}
		$privileges = ks::buildPrivileges($privilegesArray);
		
		$ks = kSessionUtils::createKSession($partnerId, $secret, $userId, $expiry, $type, $privileges);
		if(!$ks)
			throw new BorhanAPIException(APIErrors::START_SESSION_ERROR, $partnerId);
			
		$sessionInfo = new BorhanSessionInfo();
		$sessionInfo->ks = $ks->toSecureString();
		$sessionInfo->partnerId = $partnerId;
		$sessionInfo->userId = $userId;
		$sessionInfo->expiry = $ks->valid_until;
		$sessionInfo->sessionType = $type;
		$sessionInfo->privileges = $privileges;
		
		return $sessionInfo;
	}
	
};